<?php
// Autoload vendor files using the Composer autoloader.
// This line makes sure that all the necessary libraries and files are loaded automatically.
require_once 'vendor/autoload.php';

// Include our custom functions for interacting with HubSpot and MailerLite APIs.
require_once 'src/hubspotFunctions.php';
require_once 'src/mailerliteFunctions.php';

// Use the Dotenv package to load environment variables from the .env file.
// This helps keep sensitive information like API keys safe and out of the main code.
use Dotenv\Dotenv;
use MailerLite\MailerLite;

// Create an instance of Dotenv to load environment variables from the .env file.
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load(); // Load the environment variables

// Access the API keys from the environment variables.
$hubspotApiKey = $_ENV['HUBSPOT_API_KEY'];
$mailerliteApiKey = $_ENV['MAILERLITE_API_KEY'];

// Instantiate the HubSpot and MailerLite clients using the API keys.
// These clients will allow us to interact with the respective services.
$hubspot = \HubSpot\Factory::createWithAccessToken($hubspotApiKey);
$mailerlite = new MailerLite(['api_key' => $mailerliteApiKey]);


// Retrieve data from HubSpot, including contacts and their associated deals.
// $hubspotData = getContactsAndDeals($hubspot);

// Save the retrieved HubSpot data to a JSON file for easy access later.
// file_put_contents('hubspotData.json', json_encode($hubspotData, JSON_PRETTY_PRINT));

// Save the HubSpot data to another file.
// file_put_contents('files/hubspot.json', json_encode($hubspotData));


// Retrieve data from MailerLite, such as subscribers.
$mlData = getMailerLiteSubscribers($mailerlite, 1);

// Save the MailerLite data to a JSON file.
file_put_contents('files/mailerlite.json', json_encode($mlData));


// Test other functions and write their data to files.

// Retrieve all HubSpot deals and save them to a file.
$hubspotDeals = getHubSpotDealsWithHttp($hubspot);
file_put_contents('files/allHubspotDeals.json', json_encode($hubspotDeals));

// Retrieve a limited number of HubSpot deals and save them to a file.
$hubspotDeals = getLimitedHubSpotDealsWithHttp($hubspot, 1);
file_put_contents('files/hubspotDeals.json', json_encode($hubspotDeals));

// Retrieve a limited number of HubSpot quotes and save them to a file.
$hubspotQuotes = getLimitedHubSpotQuotesWithHttp($hubspot, 1);
file_put_contents('files/hubspotQuotes.json', json_encode($hubspotQuotes));


// Search for the contact by email.
$email = "barry.phillips@optusnet.com.au";
$contacts = searchHubSpotContactByEmail($hubspot, $email);

// Save the contact data to a file.
  file_put_contents('files/hubspotContactBarryPhillips.json', json_encode($contacts, JSON_PRETTY_PRINT));

// Check if the contact was found.
if ($contacts) {

  // Directly access the 'id' property of the first contact.
  $contactId = $contacts[0]['results'][0]['id'];
  $deals = getAssociatedDeals($hubspot, $contactId);

  // Save the associated deals to a file.
  file_put_contents('files/hubspotDealsBarryPhillips.json', json_encode($deals, JSON_PRETTY_PRINT));
} else {
  echo "No contact found with the email: $email\n";
}