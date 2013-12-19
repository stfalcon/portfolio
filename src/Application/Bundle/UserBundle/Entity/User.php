<?php

namespace Application\Bundle\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
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
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="imageName")
     *
     * @var File $image
     */
    protected $image;

    /**
     * @ORM\Column(type="string", length=255, name="image_name")
     *
     * @var string $imageName
     */
    protected $imageName;

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
     * @ORM\Column(type="string", length=255, name="caricature_name" )
     *
     * @var string $caricatureName
     */
    protected $caricatureName;

    /**
     * @ORM\Column(type="string", length=255, name="company_position")
     *
     * @var string $position
     */
    protected $position;

    /**
     * @ORM\Column(type="string", length=500, name="interests")
     *
     * @var string $interests
     */
    protected $interests;

    /**
     * @ORM\Column(type="string", length=100, name="drink")
     *
     * @var string $drink
     */
    protected $drink;

    /**
     * @var array $interestsList
     */
    static protected $interestsList = array(
        'musics-lover' => 'Меломаны',
        'games' => 'Геймеры',
        'books' => 'Книголюбы',
        'movies' => 'Киноманы',
        'sportsman' => 'Спортсмены',
        'art' => 'Ценители искусства',
        'adventures' => 'Искатели приключений',
        'ironman' => 'Вжелезкахковырялы',
        'cyclists' => 'Велосипедисты',
        'detectives' => 'Детективы',
        'unusual-hobbies' => 'Необычные увлечения',
    );

    /**
     * @var array $drinksList
     */
    static protected $drinksList = array(
        'beer' => 'Пиво',
        'tee' => 'Чай',
        'coffee' => 'Кофе',
        'water' => 'Вода',
    );

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\File $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $imageName
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }

    /**
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
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
     * @param string $interests
     */
    public function setInterests($interests)
    {
        $this->interests = serialize($interests);
    }

    /**
     * @return string
     */
    public function getInterests()
    {
        $result = unserialize($this->interests);

        if (!$result) {
            return array();
        }

        return $result;
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
}