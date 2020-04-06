# Server Configuration

# Apache .htaccess config

## File content

    RewriteEngine on
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php?q=$1 [L,QSA]

## Trouble Shooting

If it's not working, make sure that mod_rewrite is installed on Apache.

# nginx config

    location / {
        if (!-f $request_filename){
            set $rule_0 1$rule_0;
        }
        if (!-d $request_filename){
            set $rule_0 2$rule_0;
        }
        if ($rule_0 = "21"){
            rewrite ^/(.*)$ /index.php?\$1 last;
        }
    }
