# DMG: Read More & Search

## THE BRIEF

Create a custom WP-CLI command named like, `dmg-read-more search`
This command will take optional date-range arguments like “date-before” and “date-after” If the dates are omitted, the command will default to the last 30 days.

The command will execute a WP_Query search for Posts within the date range looking for posts containing the aforementioned Gutenberg block. 

Performance is key, this WP-CLI command will be tested against a database that has tens of millions records in the wp_posts table.

The command will log to STDOUT all Post IDs for the matching results.
If no posts are found, or any other errors encountered, output a log message.

## USAGE

1. Go to the WordPress admin dashboard, navigate to Plugins, and activate the `DMG Read More Search` plugin.
2. After activating the plugin, you can test your WP-CLI command by running the following commands in your terminal: 
`wp dmg-read-more search --date-before=2024-07-01 --date-after=2024-06-01`
3. Or, with the default date range:
`wp dmg-read-more search`







