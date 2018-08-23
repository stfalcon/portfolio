<?php

namespace Application\Bundle\DefaultBundle\Service;

use Buzz\Browser;
use Stfalcon\Bundle\EventBundle\Entity\Event;
use Stfalcon\Bundle\PortfolioBundle\Entity\Project;

/**
 * Class GitHubApiService.
 */
class GitHubApiService
{
    /** @var Browser */
    private $buzzService;

    /**
     * GitHubApiService constructor.
     *
     * @param Browser $buzzService
     */
    public function __construct(Browser $buzzService)
    {
        $this->buzzService = $buzzService;
    }

    /**
     * Get Project stars count
     *
     * @param string $githubRepoName
     *
     * @return null|int
     */
    public function getProjectStarsCountByName($githubRepoName)
    {
        $json = $this->buzzService->get(
            'https://api.github.com/repos/'.$githubRepoName,
            ['User-agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36']
        );

        $response = json_decode(
            $json->getContent(),
            true
        );

        return isset($response['stargazers_count']) ? $response['stargazers_count'] : null;
    }
}
