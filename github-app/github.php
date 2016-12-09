<?php

// A console script to test out the functionality of the app.

require_once 'vendor/autoload.php';

$options['username'] = getopt("u:")['u'];

if (!$options['username']) {
	throw new Exception("You have to provide a username OR What is github without usernames ?");
}

$options['repo'] = getopt("r:");

$options['file'] = getopt("f:");

$githubClient = new Adelowo\Github\GithubClient(new GuzzleHttp\Client());

$data = [];

if ($options['repo']) {
	$data = $githubClient->getUserRepositories($options['username'][0]);
} else {
	$data = $githubClient->getUserProfile($options['username']);
}

var_dump($data);