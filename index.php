<?php
// Autoload vendor files using the Composer autoloader.
require_once 'vendor/autoload.php';
// Include the HubSpot functions file.
require_once 'src/hubspotFunctions.php';
// Include the MailerLite functions file.
require_once 'src/mailerliteFunctions.php';

// Use the Dotenv package to load environment variables from the .env file.
use Dotenv\Dotenv;
use MailerLite\MailerLite;

// Use createImmutable() to create an immutable Dotenv instance.
$dotenv = Dotenv::createImmutable(__DIR__);
// Then load the environment variables from the .env file.
$dotenv->load();

// Once we have the environment variables, we can access them using the $_ENV superglobal
// and the key names from the .env file.
$hubspotApiKey = $_ENV['HUBSPOT_API_KEY'];
$mailerliteApiKey = $_ENV['MAILERLITE_API_KEY'];

// Next we need to instantiate the HubSpot and MailerLite clients.

/* HubSpot uses the HubSpot\Factory class to create a client instance.
The :: is the scope resolution operator, which is used to access static, constant, and overridden properties or methods of a class.
We use that to access the createWithAccessToken() method of the HubSpot\Factory class and store the result in the $hubspot variable.
*/
$hubspot = \HubSpot\Factory::createWithAccessToken($hubspotApiKey);

// MailerLite uses the MailerLite\Client class to create a client instance.
$mailerlite = new MailerLite(['api_key' => $mailerliteApiKey]);

// Now that we have the HubSpot and MailerLite clients, we can use them to interact with the respective APIs.
$hubspotData = getHubSpotContactsWithHttp($hubspot);

// Test the get all deals function.
$hubspotDeals = getHubSpotDealsWithHttp($hubspot);
file_put_contents('allHubspotDeals.json', json_encode($hubspotDeals));

// Now get the data from MailerLite
$mlData = getMailerLiteSubscribers($mailerlite, 1);

// Test the other hubspot objects.
$hubspotCompanies = getLimitedHubSpotCompaniesWithHttp($hubspot, 1);
file_put_contents('hubspotCompanies.json', json_encode($hubspotCompanies));

$hubspotDeals = getLimitedHubSpotDealsWithHttp($hubspot, 1);
file_put_contents('hubspotDeals.json', json_encode($hubspotDeals));

$hubspotQuotes = getLimitedHubSpotQuotesWithHttp($hubspot, 1);
file_put_contents('hubspotQuotes.json', json_encode($hubspotQuotes));

// Print the data from the hubspot and mailerlite to files.
// file_put_contents() writes the data to a file using the specified filename and data.
// I've used json_encode() to convert the data to JSON format before writing it to the file.
file_put_contents('hubspot.json', json_encode($hubspotData));
file_put_contents('mailerlite.json', json_encode($mlData));

