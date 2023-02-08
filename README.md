# mailpoet-anti-spam
Simple PHP code snippet to fight hotlinking when using MailPoet plugin (tested with 4.6.0).

When using MailPoet, you can add posts (with its featured image), woocommerce products (same thing) and any image you want to your emails. But this also means that those files must be accessible whatever directory they are in, from whatever webmail your users use.
This allow hotlinking if you want to be sure everyone can read your mail and see your pictures.
And Hotlinking is BAD for SEO!

To fight hotlinking, you must secure all your directories. But this way, say goodby to your pictures in your emails.

The solution is to allow just one directory tree (directory and its subdirs) and place a copy of your pictures here, and use them in your email.

To keep the ease of use of MailPoet and protect my website against hotlinking, I wrote this code snippets, using a MailPoet filter, to do so, automatically.

Enjoy, and feel free to improve!
