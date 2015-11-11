<?php

namespace Stfalcon\Bundle\PortfolioBundle\Entity;

use Application\Bundle\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * UserWithPosition class
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 *
 * @ORM\Table(name="portfolio_users_with_positions")
 * @ORM\Entity(repositoryClass="Stfalcon\Bundle\PortfolioBundle\Repository\UserWithPositionRepository")
 *
 * @UniqueEntity(
 *      fields    = {"project", "user"},
 *      errorPath = "user",
 *      message   = "This user is already partisipant on this project"
 * )
 *
 * @Gedmo\Loggable
 */
class UserWithPosition
{
    /**
     * @var int $id ID
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User $user User
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     *
     * @Gedmo\Versioned()
     */
    private $user;

    /**
     * @var Project $project Project
     *
     * @ORM\ManyToOne(targetEntity="Stfalcon\Bundle\PortfolioBundle\Entity\Project", inversedBy="usersWithPositions")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     *
     * @Gedmo\Versioned()
     */
    private $project;

    /**
     * @var string $positions Positions
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Gedmo\Versioned()
     */
    private $positions;

    /**
     * To string
     *
     * @return string
     */
    public function __toString()
    {
        $result = 'New project to user with position';

        if (null !== $this->getUser() && null !== $this->getProject()) {
            $result = $this->getProject()->getName().' - '.$this->getUser()->getFullname();
        }

        return $result;
    }

    /**
     * Get ID
     *
     * @return int ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get user
     *
     * @return User User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param User $user user
     *
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get project
     *
     * @return Project Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set project
     *
     * @param Project $project project
     *
     * @return $this
     */
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get positions
     *
     * @return string Positions
     */
    public function getPositions()
    {
        return $this->positions;
    }

    /**
     * Set positions
     *
     * @param string $positions positions
     *
     * @return $this
     */
    public function setPositions($positions)
    {
        $this->positions = $positions;

        return $this;
    }
}
