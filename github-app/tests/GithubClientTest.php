<?php

namespace Adelowo\Github\Tests;

use Adelowo\Github\GithubClient;
use Adelowo\Github\InvalidResponseException;
use GuzzleHttp\Client;
use Mockery;
use Psr\Http\Message\ResponseInterface;

class GithubClientTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Mockery\Mock
     */
    protected $httpClient;

    /**
     * @var Mockery\Mock
     */
    protected $response;

    public function setUp()
    {
        $this->httpClient = Mockery::mock(Client::class)->makePartial();
        $this->response = Mockery::mock(ResponseInterface::class)->makePartial();

        $this->httpClient->shouldReceive('get')
            ->once()
            ->andReturn($this->response);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @dataProvider getUserProfile
     */
    public function testUserProfileWasFetchedSuccessfully($response)
    {

        $this->response->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(200);

        $this->response->shouldReceive('getBody')
            ->once()
            ->withNoArgs()
            ->andReturn(\GuzzleHttp\json_encode($response));

        $userProfile = $this->getGithubClient()->getUserProfile('fabpot');

        $this->assertJsonStringEqualsJsonString(
            \GuzzleHttp\json_encode($response),
            \GuzzleHttp\json_encode($userProfile)
        );
    }

    protected function getGithubClient()
    {
        return new GithubClient($this->httpClient);
    }

    public function testUserProfileCouldNotBeFetchedBecauseAnInvalidHttpResponseWasReceived()
    {

        $this->response->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(201);

        $this->response->shouldReceive('getBody')
            ->never();

        $this->expectException(InvalidResponseException::class);

        $this->getGithubClient()->getUserProfile("fabpot");
    }

    public function testAllRepositoriesOwnedByAUserWasFetchedCorrectly()
    {
        $response = $this->getUserRepos();

        $this->response->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(200);

        $this->response->shouldReceive('getBody')
            ->once()
            ->withNoArgs()
            ->andReturn(\GuzzleHttp\json_encode($response));

        $userRepos = $this->getGithubClient()->getUserRepositories("adelowo");

        $this->assertJsonStringEqualsJsonString(
          \GuzzleHttp\json_encode($response),
            \GuzzleHttp\json_encode($userRepos)
        );
    }

    public function testAUserRepositoriesCouldNotBeFetchedBecauseAnInvalidHttpResponseWasReceived()
    {

        $this->response->shouldReceive('getStatusCode')
            ->once()
            ->andReturn(201);

        $this->response->shouldReceive('getBody')
            ->never();

        $this->expectException(InvalidResponseException::class);

        $this->getGithubClient()->getUserRepositories("adelowo");
    }

    public function getUserProfile()
    {
        return [
            [
                "login" => "fabpot",
                "id" => 47313,
                "avatar_url" => "https://avatars.githubusercontent.com/u/47313?v=3",
                "gravatar_id" => "",
                "url" => "https://api.github.com/users/fabpot",
                "html_url" => "https://github.com/fabpot",
                "followers_url" => "https://api.github.com/users/fabpot/followers",
                "following_url" => "https://api.github.com/users/fabpot/following{/other_user}",
                "gists_url" => "https://api.github.com/users/fabpot/gists{/gist_id}",
                "starred_url" => "https://api.github.com/users/fabpot/starred{/owner}{/repo}",
                "subscriptions_url" => "https://api.github.com/users/fabpot/subscriptions",
                "organizations_url" => "https://api.github.com/users/fabpot/orgs",
                "repos_url" => "https://api.github.com/users/fabpot/repos",
                "events_url" => "https://api.github.com/users/fabpot/events{/privacy}",
                "received_events_url" => "https://api.github.com/users/fabpot/received_events",
                "type" => "User",
                "site_admin" => false,
                "name" => "Fabien Potencier",
                "company" => "SensioLabs",
                "blog" => "http://fabien.potencier.org/",
                "location" => "San Francisco",
                "email" => "fabien@symfony.com",
                "hireable" => true,
                "bio" => null,
                "public_repos" => 19,
                "public_gists" => 8,
                "followers" => 6505,
                "following" => 0,
                "created_at" => "2009-01-17T13:42:51Z",
                "updated_at" => "2016-11-30T09:52:54Z"
            ]
        ];
    }

    protected function getUserRepos()
    {
        return [
            [
                "id" => 73918229,
                "name" => "address-bok",
                "full_name" => "adelowo/address-bok",
                "owner" => [
                    "login" => "adelowo",
                    "id" => 12677701,
                    "avatar_url" => "https://avatars.githubusercontent.com/u/12677701?v=3",
                    "gravatar_id" => "",
                    "url" => "https://api.github.com/users/adelowo",
                    "html_url" => "https://github.com/adelowo",
                    "followers_url" => "https://api.github.com/users/adelowo/followers",
                    "following_url" => "https://api.github.com/users/adelowo/following{/other_user}",
                    "gists_url" => "https://api.github.com/users/adelowo/gists{/gist_id}",
                    "starred_url" => "https://api.github.com/users/adelowo/starred{/owner}{/repo}",
                    "subscriptions_url" => "https://api.github.com/users/adelowo/subscriptions",
                    "organizations_url" => "https://api.github.com/users/adelowo/orgs",
                    "repos_url" => "https://api.github.com/users/adelowo/repos",
                    "events_url" => "https://api.github.com/users/adelowo/events{/privacy}",
                    "received_events_url" => "https://api.github.com/users/adelowo/received_events",
                    "type" => "User",
                    "site_admin" => false
                ],
                "private" => false,
                "html_url" => "https://github.com/adelowo/address-bok",
                "description" => "Some Sample project",
                "fork" => false,
                "url" => "https://api.github.com/repos/adelowo/address-bok",
                "forks_url" => "https://api.github.com/repos/adelowo/address-bok/forks",
                "keys_url" => "https://api.github.com/repos/adelowo/address-bok/keys{/key_id}",
                "collaborators_url" => "https://api.github.com/repos/adelowo/address-bok/collaborators{/collaborator}",
                "teams_url" => "https://api.github.com/repos/adelowo/address-bok/teams",
                "hooks_url" => "https://api.github.com/repos/adelowo/address-bok/hooks",
                "issue_events_url" => "https://api.github.com/repos/adelowo/address-bok/issues/events{/number}",
                "events_url" => "https://api.github.com/repos/adelowo/address-bok/events",
                "assignees_url" => "https://api.github.com/repos/adelowo/address-bok/assignees{/user}",
                "branches_url" => "https://api.github.com/repos/adelowo/address-bok/branches{/branch}",
                "tags_url" => "https://api.github.com/repos/adelowo/address-bok/tags",
                "blobs_url" => "https://api.github.com/repos/adelowo/address-bok/git/blobs{/sha}",
                "git_tags_url" => "https://api.github.com/repos/adelowo/address-bok/git/tags{/sha}",
                "git_refs_url" => "https://api.github.com/repos/adelowo/address-bok/git/refs{/sha}",
                "trees_url" => "https://api.github.com/repos/adelowo/address-bok/git/trees{/sha}",
                "statuses_url" => "https://api.github.com/repos/adelowo/address-bok/statuses/{sha}",
                "languages_url" => "https://api.github.com/repos/adelowo/address-bok/languages",
                "stargazers_url" => "https://api.github.com/repos/adelowo/address-bok/stargazers",
                "contributors_url" => "https://api.github.com/repos/adelowo/address-bok/contributors",
                "subscribers_url" => "https://api.github.com/repos/adelowo/address-bok/subscribers",
                "subscription_url" => "https://api.github.com/repos/adelowo/address-bok/subscription",
                "commits_url" => "https://api.github.com/repos/adelowo/address-bok/commits{/sha}",
                "git_commits_url" => "https://api.github.com/repos/adelowo/address-bok/git/commits{/sha}",
                "comments_url" => "https://api.github.com/repos/adelowo/address-bok/comments{/number}",
                "issue_comment_url" => "https://api.github.com/repos/adelowo/address-bok/issues/comments{/number}",
                "contents_url" => "https://api.github.com/repos/adelowo/address-bok/contents/{+path}",
                "compare_url" => "https://api.github.com/repos/adelowo/address-bok/compare/{base}...{head}",
                "merges_url" => "https://api.github.com/repos/adelowo/address-bok/merges",
                "archive_url" => "https://api.github.com/repos/adelowo/address-bok/{archive_format}{/ref}",
                "downloads_url" => "https://api.github.com/repos/adelowo/address-bok/downloads",
                "issues_url" => "https://api.github.com/repos/adelowo/address-bok/issues{/number}",
                "pulls_url" => "https://api.github.com/repos/adelowo/address-bok/pulls{/number}",
                "milestones_url" => "https://api.github.com/repos/adelowo/address-bok/milestones{/number}",
                "notifications_url" => "https://api.github.com/repos/adelowo/address-bok/notifications{?since,all,participating}",
                "labels_url" => "https://api.github.com/repos/adelowo/address-bok/labels{/name}",
                "releases_url" => "https://api.github.com/repos/adelowo/address-bok/releases{/id}",
                "deployments_url" => "https://api.github.com/repos/adelowo/address-bok/deployments",
                "created_at" => "2016-11-16T12:30:10Z",
                "updated_at" => "2016-11-23T14:52:23Z",
                "pushed_at" => "2016-11-23T14:53:45Z",
                "git_url" => "git://github.com/adelowo/address-bok.git",
                "ssh_url" => "git@github.com:adelowo/address-bok.git",
                "clone_url" => "https://github.com/adelowo/address-bok.git",
                "svn_url" => "https://github.com/adelowo/address-bok",
                "homepage" => "",
                "size" => 59,
                "stargazers_count" => 1,
                "watchers_count" => 1,
                "language" => "PHP",
                "has_issues" => true,
                "has_downloads" => true,
                "has_wiki" => true,
                "has_pages" => false,
                "forks_count" => 0,
                "mirror_url" => null,
                "open_issues_count" => 0,
                "forks" => 0,
                "open_issues" => 0,
                "watchers" => 1,
                "default_branch" => "master"
            ],
            [
                "id" => 48253221,
                "name" => "adelowo.github.io",
                "full_name" => "adelowo/adelowo.github.io",
                "owner" => [
                    "login" => "adelowo",
                    "id" => 12677701,
                    "avatar_url" => "https://avatars.githubusercontent.com/u/12677701?v=3",
                    "gravatar_id" => "",
                    "url" => "https://api.github.com/users/adelowo",
                    "html_url" => "https://github.com/adelowo",
                    "followers_url" => "https://api.github.com/users/adelowo/followers",
                    "following_url" => "https://api.github.com/users/adelowo/following{/other_user}",
                    "gists_url" => "https://api.github.com/users/adelowo/gists{/gist_id}",
                    "starred_url" => "https://api.github.com/users/adelowo/starred{/owner}{/repo}",
                    "subscriptions_url" => "https://api.github.com/users/adelowo/subscriptions",
                    "organizations_url" => "https://api.github.com/users/adelowo/orgs",
                    "repos_url" => "https://api.github.com/users/adelowo/repos",
                    "events_url" => "https://api.github.com/users/adelowo/events{/privacy}",
                    "received_events_url" => "https://api.github.com/users/adelowo/received_events",
                    "type" => "User",
                    "site_admin" => false
                ],
                "private" => false,
                "html_url" => "https://github.com/adelowo/adelowo.github.io",
                "description" => "My porfolio and blog viewable. It actually is still a work in progress",
                "fork" => false,
                "url" => "https://api.github.com/repos/adelowo/adelowo.github.io",
                "forks_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/forks",
                "keys_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/keys{/key_id}",
                "collaborators_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/collaborators{/collaborator}",
                "teams_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/teams",
                "hooks_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/hooks",
                "issue_events_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/issues/events{/number}",
                "events_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/events",
                "assignees_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/assignees{/user}",
                "branches_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/branches{/branch}",
                "tags_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/tags",
                "blobs_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/git/blobs{/sha}",
                "git_tags_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/git/tags{/sha}",
                "git_refs_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/git/refs{/sha}",
                "trees_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/git/trees{/sha}",
                "statuses_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/statuses/{sha}",
                "languages_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/languages",
                "stargazers_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/stargazers",
                "contributors_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/contributors",
                "subscribers_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/subscribers",
                "subscription_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/subscription",
                "commits_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/commits{/sha}",
                "git_commits_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/git/commits{/sha}",
                "comments_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/comments{/number}",
                "issue_comment_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/issues/comments{/number}",
                "contents_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/contents/{+path}",
                "compare_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/compare/{base}...{head}",
                "merges_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/merges",
                "archive_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/{archive_format}{/ref}",
                "downloads_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/downloads",
                "issues_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/issues{/number}",
                "pulls_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/pulls{/number}",
                "milestones_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/milestones{/number}",
                "notifications_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/notifications{?since,all,participating}",
                "labels_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/labels{/name}",
                "releases_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/releases{/id}",
                "deployments_url" => "https://api.github.com/repos/adelowo/adelowo.github.io/deployments",
                "created_at" => "2015-12-18T19:40:06Z",
                "updated_at" => "2016-11-16T12:37:34Z",
                "pushed_at" => "2016-12-06T17:38:20Z",
                "git_url" => "git://github.com/adelowo/adelowo.github.io.git",
                "ssh_url" => "git@github.com:adelowo/adelowo.github.io.git",
                "clone_url" => "https://github.com/adelowo/adelowo.github.io.git",
                "svn_url" => "https://github.com/adelowo/adelowo.github.io",
                "homepage" => "",
                "size" => 6227,
                "stargazers_count" => 1,
                "watchers_count" => 1,
                "language" => "HTML",
                "has_issues" => true,
                "has_downloads" => true,
                "has_wiki" => true,
                "has_pages" => true,
                "forks_count" => 0,
                "mirror_url" => null,
                "open_issues_count" => 0,
                "forks" => 0,
                "open_issues" => 0,
                "watchers" => 1,
                "default_branch" => "master"
            ],
            [
                "id" => 61086795,
                "name" => "awesome-growth-hacking",
                "full_name" => "adelowo/awesome-growth-hacking",
                "owner" => [
                    "login" => "adelowo",
                    "id" => 12677701,
                    "avatar_url" => "https://avatars.githubusercontent.com/u/12677701?v=3",
                    "gravatar_id" => "",
                    "url" => "https://api.github.com/users/adelowo",
                    "html_url" => "https://github.com/adelowo",
                    "followers_url" => "https://api.github.com/users/adelowo/followers",
                    "following_url" => "https://api.github.com/users/adelowo/following{/other_user}",
                    "gists_url" => "https://api.github.com/users/adelowo/gists{/gist_id}",
                    "starred_url" => "https://api.github.com/users/adelowo/starred{/owner}{/repo}",
                    "subscriptions_url" => "https://api.github.com/users/adelowo/subscriptions",
                    "organizations_url" => "https://api.github.com/users/adelowo/orgs",
                    "repos_url" => "https://api.github.com/users/adelowo/repos",
                    "events_url" => "https://api.github.com/users/adelowo/events{/privacy}",
                    "received_events_url" => "https://api.github.com/users/adelowo/received_events",
                    "type" => "User",
                    "site_admin" => false
                ],
                "private" => false,
                "html_url" => "https://github.com/adelowo/awesome-growth-hacking",
                "description" => "Awesome Growth Hacking resources",
                "fork" => true,
                "url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking",
                "forks_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/forks",
                "keys_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/keys{/key_id}",
                "collaborators_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/collaborators{/collaborator}",
                "teams_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/teams",
                "hooks_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/hooks",
                "issue_events_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/issues/events{/number}",
                "events_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/events",
                "assignees_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/assignees{/user}",
                "branches_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/branches{/branch}",
                "tags_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/tags",
                "blobs_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/git/blobs{/sha}",
                "git_tags_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/git/tags{/sha}",
                "git_refs_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/git/refs{/sha}",
                "trees_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/git/trees{/sha}",
                "statuses_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/statuses/{sha}",
                "languages_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/languages",
                "stargazers_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/stargazers",
                "contributors_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/contributors",
                "subscribers_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/subscribers",
                "subscription_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/subscription",
                "commits_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/commits{/sha}",
                "git_commits_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/git/commits{/sha}",
                "comments_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/comments{/number}",
                "issue_comment_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/issues/comments{/number}",
                "contents_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/contents/{+path}",
                "compare_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/compare/{base}...{head}",
                "merges_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/merges",
                "archive_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/{archive_format}{/ref}",
                "downloads_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/downloads",
                "issues_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/issues{/number}",
                "pulls_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/pulls{/number}",
                "milestones_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/milestones{/number}",
                "notifications_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/notifications{?since,all,participating}",
                "labels_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/labels{/name}",
                "releases_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/releases{/id}",
                "deployments_url" => "https://api.github.com/repos/adelowo/awesome-growth-hacking/deployments",
                "created_at" => "2016-06-14T02:50:38Z",
                "updated_at" => "2016-06-14T02:50:38Z",
                "pushed_at" => "2016-04-03T06:02:18Z",
                "git_url" => "git://github.com/adelowo/awesome-growth-hacking.git",
                "ssh_url" => "git@github.com:adelowo/awesome-growth-hacking.git",
                "clone_url" => "https://github.com/adelowo/awesome-growth-hacking.git",
                "svn_url" => "https://github.com/adelowo/awesome-growth-hacking",
                "homepage" => "",
                "size" => 21,
                "stargazers_count" => 0,
                "watchers_count" => 0,
                "language" => null,
                "has_issues" => false,
                "has_downloads" => true,
                "has_wiki" => true,
                "has_pages" => false,
                "forks_count" => 0,
                "mirror_url" => null,
                "open_issues_count" => 0,
                "forks" => 0,
                "open_issues" => 0,
                "watchers" => 0,
                "default_branch" => "master"
            ]
        ];
    }
}

