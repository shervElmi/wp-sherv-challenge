# Sherv Developer Applicant Challenge

## Requirements

Using the GET accessible endpoint <http://api.strategy11.com/wp-json/challenge/v1/1> (there are no parameters to/from required), create an AJAX endpoint in WordPress that:

- Can be used when logged out or in;
- Calls the above endpoint to get the data to return;
- Which when called always returns the data, but regardless of when/how many times it is called should not request the data from our server more than 1 time per hour.
- Create a shortcode for the frontend, that when loaded uses Javascript to contact your AJAX endpoint and display the data returned formatted into a table-like display;
- Create a WP CLI command that can be used to force the refresh of this data the next time the AJAX endpoint is called;
- Create a WordPress admin page which displays this data in the style of the admin page of the WordPress plugin Formidable Forms that includes the Formidable logo and header. Include a button to refresh the data.
- Create unit tests that check the following:
  - Is the request run multiple times an hour?
  - Is the table showing the expected results?

Ensure to properly escape, sanitize and validate data in each step as appropriate using built in PHP and WordPress functions. The code you submit should not be built from a boilerplate.

## Installation

Follow these steps to set up the environment:

1. Install PHP dependencies:

   ```bash
   composer install
   ```

2. Install JavaScript dependencies:

   ```bash
   npm install
   ```

## Minimum Requirements and Dependencies

This project requires:

- PHP version 7.2+
- WordPress version 5.8+
- Node.js version 16+
- NPM version 8+

## Development

- Start Development Environment: `npm start`
- Build for Production: `npm run build`
- Lint Code: `npm run lint`
- Format Code: `npm run format`
- Run Tests: `npm run test`

## License and Copyright

© [2022] [Strategy11](https://strategy11.com/). Licensed under the [GNU GPL, Version 3.0](https://www.gnu.org/licenses/gpl-3.0.en.html). This program is distributed without any warranty. See the license for more details.
