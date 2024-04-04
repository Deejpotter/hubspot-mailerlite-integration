<?php
// Autoload vendor files using the Composer autoloader.
require_once 'vendor/autoload.php';

// Declare the type for the MailerLite exceptions.
use MailerLite\Exceptions\MailerLiteHttpException;

/**
 * Fetches a list of subscribers from MailerLite.
 *
 * @param \MailerLite\MailerLite $mailerlite The MailerLite client object.
 * @param int $limit The maximum number of subscribers to fetch.
 * @return array|null The list of subscribers, or null if an error occurred.
 * @throws MailerLite\Exceptions\MailerLiteHttpException If an HTTP exception occurs.
 */
function getMailerLiteSubscribers($mailerlite, int $limit = 10): ?array
{
  try {
    // Get all subscribers from MailerLite
    $mailerliteSubscribers = $mailerlite->subscribers->get(
      [
        'limit' => $limit,
      ]
    );

    // Finally, return the subscribers.
    return $mailerliteSubscribers;

    // Catch any exceptions if anything happens.
  } catch (MailerLiteHttpException $e) {
    // If an HTTP exception occurs (e.g., due to invalid API credentials),
    // print the error message and return null.
    echo "Error: " . $e->getMessage();
    return null;
  }
}

/**
 * Creates a new subscriber in MailerLite.
 *
 * @param object $mailerlite The MailerLite client object.
 * @param string $email The email address of the new subscriber.
 * @param string $name The name of the new subscriber.
 * @return object|null The new subscriber, or null if an error occurred.
 */
function createMailerLiteSubscriber($mailerlite, $email, $name)
{
  try {
    // Create a new subscriber in MailerLite
    $newSubscriber = $mailerlite->subscribers->create(
      [
        'email' => $email,
        'name' => $name,
      ]
    );

    // Finally, return the new subscriber.
    return $newSubscriber;

    // Catch any exceptions if anything happens.
  } catch (MailerLiteHttpException $e) {
    // If an HTTP exception occurs (e.g., due to the email already being subscribed),
    // print the error message and return null.
    echo "Error: " . $e->getMessage();
    return null;
  }
}