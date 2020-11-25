((nil . ((mode . flycheck)
		 (mode . company)
		 (mode . edep)
		 (mode . subword)
		 (tab-width . 4)
		 (c-basic-offset . 4)
		 (indent-tabs-mode . t)
		 (eval . (progn (when (fboundp 'delete-trailing-whitespace)
						  (delete-trailing-whitespace))
						  (tabify (point-min) (point-max))))
		 (c-hanging-braces-alist
		  (defun-open after)
		  (block-open after)
		  (defun-close))
		 (c-offsets-alist . (
							 (access-label . -)
							 (annotation-top-cont . 0)
							 (annotation-var-cont . +)
							 (arglist-close . php-lineup-arglist-close)
							 (arglist-cont-nonempty first
													php-lineup-cascaded-calls
													c-lineup-arglist)
							 (arglist-intro . php-lineup-arglist-intro)
							 (block-close . 0)
							 (block-open . 0)
							 (brace-entry-open . 0)
							 (brace-list-close . 0)
							 (brace-list-entry . 0)
							 (brace-list-intro . +)
							 (brace-list-open . 0)
							 (c . c-lineup-C-comments)
							 (case-label . 0)
							 (catch-clause . 0)
							 (class-close . 0)
							 (comment-intro . 0)
							 (composition-close . 0)
							 (composition-open . 0)
							 (cpp-define-intro c-lineup-cpp-define +)
							 (cpp-macro . [0])
							 (cpp-macro-cont . +)
							 (defun-block-intro . +)
							 (defun-close . 0)
							 (defun-open . 0)
							 (do-while-closure . 0)
							 (else-clause . 0)
							 (extern-lang-close . 0)
							 (extern-lang-open . 0)
							 (friend . 0)
							 (func-decl-cont . +)
							 (inclass . +)
							 (incomposition . +)
							 (inexpr-class . +)
							 (inexpr-statement . +)
							 (inextern-lang . +)
							 (inher-cont . c-lineup-multi-inher)
							 (inher-intro . +)
							 (inlambda . 0)
							 (inline-close . 0)
							 (inline-open . 0)
							 (inmodule . +)
							 (innamespace . +)
							 (knr-argdecl . 0)
							 (knr-argdecl-intro . +)
							 (label . +)
							 (lambda-intro-cont . +)
							 (member-init-cont . c-lineup-multi-inher)
							 (member-init-intro . +)
							 (module-close . 0)
							 (module-open . 0)
							 (namespace-close . 0)
							 (namespace-open . 0)
							 (statement . 0)
							 (statement-block-intro . +)
							 (statement-case-intro . +)
							 (statement-case-open . 0)
							 (statement-cont first
											 php-lineup-cascaded-calls
											 php-lineup-string-cont +)
							 (stream-op . c-lineup-streamop)
							 (string . c-lineup-dont-change)
							 (substatement . +)
							 (substatement-label . 2)
							 (substatement-open . 0)
							 (template-args-cont c-lineup-template-args +)
							 (topmost-intro . 0)
							 (topmost-intro-cont first php-lineup-cascaded-calls +)
							 ))
		 )))