<?php

namespace Application\Bundle\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * User Class
 *
 * @ORM\Entity(repositoryClass="Application\Bundle\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="users")
 * @Gedmo\TranslationEntity(class="Application\Bundle\UserBundle\Entity\UserTranslation")
 * @Vich\Uploadable
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="avatarName")
     *
     * @var File $image
     */
    protected $avatar;

    /**
     * @ORM\Column(type="string", length=255, name="avatar_name", nullable=true)
     *
     * @var string $imageName
     */
    protected $avatarName;

    /**
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="user_caricature", fileNameProperty="caricatureName")
     *
     * @var File $caricature
     */
    protected $caricature;

    /**
     * @ORM\Column(type="string", length=255, name="caricature_name", nullable=true )
     *
     * @var string $caricatureName
     */
    protected $caricatureName;

    /**
     * @ORM\Column(type="string", length=255, name="company_position", nullable=true)
     * @Gedmo\Translatable(fallback=true)
     *
     * @var string $position
     */
    protected $position;

    /**
     * @ORM\Column(type="array", length=500, name="interests", nullable=true)
     *
     * @var array $interests
     */
    protected $interests;

    /**
     * @ORM\Column(type="string", length=100, name="drink", nullable=true)
     *
     * @var string $drink
     */
    protected $drink;

    /**
     * @var ArrayCollection
     *
     *  @ORM\ManyToMany(targetEntity="Group")
     *  @ORM\JoinTable(name="users_users_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     *      )
     */
    protected $groups;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $ordering = 0;

    /**
     * @var string $description Description
     *
     * @Gedmo\Translatable(fallback=true)
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var array $interestsList
     */
    static protected $interestsList = array(
        'musics_lover' => 'Меломаны',
        'games' => 'Геймеры',
        'books' => 'Книголюбы',
        'movies' => 'Киноманы',
        'sportsman' => 'Спортсмены',
        'art' => 'Ценители искусства',
        'adventures' => 'Искатели приключений',
        'ironman' => 'Вжелезкахковырялы',
        'cyclists' => 'Велосипедисты',
        'detectives' => 'Детективы',
        'unusual_hobbies' => 'Необычные увлечения',
    );

    /**
     * @var array $drinksList
     */
    static protected $drinksList = array(
        'beer' => 'Пиво',
        'tea' => 'Чай',
        'coffee' => 'Кофе',
        'water' => 'Вода',
    );

    /**
     * @Gedmo\Translatable(fallback=true)
     * @var string
     */
    protected $firstname;

    /**
     * @Gedmo\Translatable(fallback=true)
     * @var string
     */
    protected $lastname;

    /**
     * @ORM\OneToMany(
     *   targetEntity="UserTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

    /**
     * @Gedmo\Locale
     */
    private $userLocale;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->groups = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\File $caricature
     */
    public function setCaricature($caricature)
    {
        $this->caricature = $caricature;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function getCaricature()
    {
        return $this->caricature;
    }

    /**
     * @param string $caricatureName
     */
    public function setCaricatureName($caricatureName)
    {
        $this->caricatureName = $caricatureName;
    }

    /**
     * @return string
     */
    public function getCaricatureName()
    {
        return $this->caricatureName;
    }

    /**
     * @param array $interests
     */
    public function setInterests($interests)
    {
        $this->interests = $interests;
    }

    /**
     * @return array
     */
    public function getInterests()
    {
        return $this->interests;
    }

    /**
     * @return array
     */
    public function getInterestsValues()
    {
        return array_values($this->interests);
    }

    /**
     * @param string $drink
     */
    public function setDrink($drink)
    {
        $this->drink = $drink;
    }

    /**
     * @return string
     */
    public function getDrink()
    {
        return $this->drink;
    }

    /**
     * @return array
     */
    static public function getInterestsList()
    {
        return self::$interestsList;
    }

    /**
     * @return array
     */
    public static function getDrinksList()
    {
        return self::$drinksList;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\File $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param string $avatarName
     */
    public function setAvatarName($avatarName)
    {
        $this->avatarName = $avatarName;
    }

    /**
     * @return string
     */
    public function getAvatarName()
    {
        return $this->avatarName;
    }

    /**
     * @param \Application\Bundle\UserBundle\Entity\ArrayCollection $groups
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;
    }

    /**
     * @return \Application\Bundle\UserBundle\Entity\ArrayCollection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param int $ordering
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;
    }

    /**
     * @return int
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * @param UserTranslation $userTranslation
     */
    public function addTranslations(UserTranslation $userTranslation)
    {
        if (!$this->translations->contains($userTranslation)) {
            $this->translations->add($userTranslation);
            $userTranslation->setObject($this);
        }
    }
    /**
     * @param UserTranslation $userTranslation
     */
    public function addTranslation(UserTranslation $userTranslation)
    {
        if (!$this->translations->contains($userTranslation)) {
            $this->translations->add($userTranslation);
            $userTranslation->setObject($this);
        }
    }

    /**
     * @param UserTranslation $userTranslation
     */
    public function removeTranslation(UserTranslation $userTranslation)
    {
        $this->translations->removeElement($userTranslation);
    }

    /**
     * @param ArrayCollection $translations
     */
    public function setTranslations($translations)
    {
        $this->translations = $translations;
    }

    /**
     * @param string $locale
     */
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param mixed $locale
     */
    public function setLocale($locale)
    {
        $this->userLocale = $locale;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->userLocale;
    }

    /**
     * Get description
     *
     * @return string Description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description Description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
