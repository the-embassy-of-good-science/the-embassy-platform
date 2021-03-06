<?php

/**
 * A small wrapper around ObjectCache to manage
 * storing the last time a user has seen notifications
 */
class EchoSeenTime {

	/**
	 * Allowed notification types
	 * @var array
	 */
	private static $allowedTypes = [ 'alert', 'message' ];

	/**
	 * @var User
	 */
	private $user;

	/**
	 * @param User $user A logged in user
	 */
	private function __construct( User $user ) {
		$this->user = $user;
	}

	/**
	 * @param User $user
	 * @return EchoSeenTime
	 */
	public static function newFromUser( User $user ) {
		return new self( $user );
	}

	/**
	 * Hold onto a cache for our operations. Static so it can reuse the same
	 * in-process cache in different instances.
	 *
	 * @return BagOStuff
	 */
	private static function cache() {
		static $c = null;

		// Use main stash for persistent storage, and
		// wrap it with CachedBagOStuff for an in-process
		// cache. (T144534)
		if ( $c === null ) {
			$c = new CachedBagOStuff(
				ObjectCache::getMainStashInstance()
			);
		}

		return $c;
	}

	/**
	 * @param string $type Type of seen time to get
	 * @param int $format Format to return time in, defaults to TS_MW
	 * @return string|bool Timestamp in specified format, or false if no stored time
	 */
	public function getTime( $type = 'all', $format = TS_MW ) {
		$vals = [];
		if ( $type === 'all' ) {
			foreach ( self::$allowedTypes as $allowed ) {
				// Use TS_MW, then convert later, so max works properly for
				// all formats.
				$vals[] = $this->getTime( $allowed, TS_MW );
			}

			return wfTimestamp( $format, min( $vals ) );
		}

		if ( !$this->validateType( $type ) ) {
			return false;
		}

		$data = self::cache()->get( $this->getMemcKey( $type ) );

		if ( $data === false ) {
			// Check if the user still has it set in their preferences
			$data = $this->user->getOption( 'echo-seen-time', false );
		}

		if ( $data === false ) {
			// There is still no time set, so set time to the UNIX epoch.
			// We can't remember their real seen time, so reset everything to
			// unseen.
			$data = wfTimestamp( TS_MW, 1 );
			$this->setTime( $data, $type );
		}
		return wfTimestamp( $format, $data );
	}

	/**
	 * Sets the seen time
	 *
	 * @param string $time Time, in TS_MW format
	 * @param string $type Type of seen time to set
	 */
	public function setTime( $time, $type = 'all' ) {
		if ( $type === 'all' ) {
			foreach ( self::$allowedTypes as $allowed ) {
				$this->setTime( $time, $allowed );
			}
			return;
		}

		if ( !$this->validateType( $type ) ) {
			return;
		}

		// Write to the in-memory cache immediately, and defer writing to
		// the real cache
		$key = $this->getMemcKey( $type );
		$cache = self::cache();
		$cache->set( $key, $time, 0, BagOStuff::WRITE_CACHE_ONLY );
		DeferredUpdates::addCallableUpdate( function () use ( $key, $time, $cache ) {
			$cache->set( $key, $time );
		} );
	}

	/**
	 * Validate the given type, make sure it is allowed.
	 *
	 * @param string $type Given type
	 * @return bool Type is allowed
	 */
	private function validateType( $type ) {
		return in_array( $type, self::$allowedTypes );
	}

	/**
	 * Build a memcached key.
	 *
	 * @param string $type Given notification type
	 * @return string Memcached key
	 */
	protected function getMemcKey( $type = 'all' ) {
		$localKey = wfMemcKey( 'echo', 'seen', $type, 'time', $this->user->getId() );

		if ( !$this->user->getOption( 'echo-cross-wiki-notifications' ) ) {
			return $localKey;
		}

		$lookup = CentralIdLookup::factory();
		$globalId = $lookup->centralIdFromLocalUser( $this->user, CentralIdLookup::AUDIENCE_RAW );

		if ( !$globalId ) {
			return $localKey;
		}

		return wfGlobalCacheKey( 'echo', 'seen', $type, 'time', $globalId );
	}
}
