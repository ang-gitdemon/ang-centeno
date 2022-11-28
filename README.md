# Angel Centeno Site
## Init

For first-time users, ensure you have WP CLI, Gulp, NPM.

Local config is through Valet Laravel.

First, clone repo into desired folder.

Then from command line:
1. `wp core download`
2. `wp config create --dbname=wp_abo12 --dbprefix=COEweE_ --dbuser=root` OR if root pw is given `wp config create --dbname=wp_abo12 --dbprefix=COEweE_ --dbuser=root --dbpass=root`
3. create a new empty db called `wp_abo12` in Sequel Ace
4. `wp core install --url=destination-brides.test --title=DestinationBrides --admin_user=admin --admin_password=admin --admin_email=wp@adnama.co `

Congrats, your local should now be running WP.