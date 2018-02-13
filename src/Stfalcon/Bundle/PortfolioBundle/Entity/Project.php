<?php

namespace Stfalcon\Bundle\PortfolioBundle\Entity;

use Application\Bundle\MediaBundle\Entity\Media;
use Application\Bundle\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Project entity.
 *
 * @ORM\Table(name="portfolio_projects")
 * @ORM\Entity(repositoryClass="Stfalcon\Bundle\PortfolioBundle\Repository\ProjectRepository")
 *
 * @Gedmo\TranslationEntity(class="Stfalcon\Bundle\PortfolioBundle\Entity\ProjectTranslation")
 *
 * @Vich\Uploadable
 */
class Project implements Translatable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "3"
     * )
     * @Gedmo\Translatable(fallback=true)
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name = '';

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "3"
     * )
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "10"
     * )
     * @Gedmo\Translatable(fallback=true)
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "3"
     * )
     * @Gedmo\Translatable(fallback=true)
     *
     * @ORM\Column(name="short_description", type="text")
     */
    private $shortDescription;

    /**
     * @var string
     *
     * @Gedmo\Translatable(fallback=true)
     *
     * @ORM\Column(name="additional_info", type="text", options={"default":""}, nullable=true)
     */
    private $additionalInfo;

    /**
     * @var string
     *
     * @Assert\Url
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="update")
     */
    private $updated;

    /**
     * @var File
     *
     * @Assert\File(
     *     maxSize="4M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="project_image", fileNameProperty="image")
     */
    protected $imageFile;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="background_color", type="string", length=7, options={"default":"#4D9CC9"}, nullable=false)
     */
    private $backgroundColor = '#4D9CC9';

    /**
     * @var int
     *
     * @ORM\Column(name="ordernum", type="integer")
     */
    private $ordernum = 0;

    /**
     * Check if this project can be published on main page of the site.
     *
     * @var bool
     *
     * @ORM\Column(name="onFrontPage", type="boolean")
     */
    private $onFrontPage = true;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Stfalcon\Bundle\PortfolioBundle\Entity\Category", inversedBy="projects")
     * @ORM\JoinTable(name="portfolio_projects_categories",
     *   joinColumns={
     *     @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     *   }
     * )
     */
    private $categories;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Application\Bundle\UserBundle\Entity\User")
     * @ORM\JoinTable(name="projects_participants",
     *   joinColumns={
     *     @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   }
     * )
     * @ORM\OrderBy({"ordering" = "ASC"})
     */
    protected $participants;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Application\Bundle\MediaBundle\Entity\GalleryHasMedia",cascade={"persist"})
     * @ORM\JoinTable(name="projects_media",
     *   joinColumns={
     *     @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="media_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $media;

    /**
     * @var string
     *
     * @Gedmo\Translatable(fallback=true)
     *
     * @ORM\Column(name="tags", type="string", nullable=true, length=255)
     */
    protected $tags;

    /**
     * @var bool
     *
     * @ORM\Column(name="published", type="boolean")
     */
    protected $published;

    /**
     * @var bool
     *
     * @ORM\Column(name="shadow", type="boolean", options={"default" = true})
     */
    protected $shadow = true;

    /**
     * @ORM\OneToMany(
     *   targetEntity="ProjectTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *   targetEntity="Stfalcon\Bundle\PortfolioBundle\Entity\ProjectReview",
     *   mappedBy="project",
     *   cascade={"persist", "remove"},
     *   orphanRemoval=true
     * )
     */
    private $projectReviews;

    /**
     * @Gedmo\Locale
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_keywords", type="text", nullable=true)
     *
     * @Gedmo\Translatable(fallback=true)
     */
    private $metaKeywords;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_description", type="text", nullable=true)
     *
     * @Gedmo\Translatable(fallback=true)
     */
    private $metaDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="case_content", type="text", nullable=true)
     *
     * @Gedmo\Translatable(fallback=true)
     */
    private $caseContent;

    /**
     * @var bool
     *
     * @ORM\Column(name="show_case", type="boolean")
     */
    private $showCase = false;

    /**
     * @ORM\ManyToMany(targetEntity="Project")
     * @ORM\JoinTable(name="relative_projects_ref",
     *      joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="relative_project_id", referencedColumnName="id", unique=true)}
     *      )
     **/
    private $relativeProjects;

    /**
     * @var Collection|UserWithPosition[] Users with positions
     *
     * @ORM\OneToMany(targetEntity="UserWithPosition", mappedBy="project", cascade={"persist", "remove"}, orphanRemoval=true)
     *
     * @Assert\Type(type="object")
     */
    private $usersWithPositions;

    /**
     * Initialization properties for new project entity.
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->participants = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->published = true;
        $this->translations = new ArrayCollection();
        $this->relativeProjects = new ArrayCollection();
        $this->usersWithPositions = new ArrayCollection();
        $this->projectReviews = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * @param string $backgroundColor
     *
     * @return $this
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $result = 'New Project';

        if (null !== $this->getId()) {
            $result = $this->getName();
        }

        return $result;
    }

    /**
     * @return mixed
     */
    public function getProjectReviews()
    {
        return $this->projectReviews;
    }

    /**
     * @param mixed $projectReviews
     *
     * @return $this
     */
    public function setProjectReviews($projectReviews)
    {
        $this->projectReviews = $projectReviews;

        return $this;
    }

    /**
     * @param ProjectReview $projectReview
     *
     * @return $this
     */
    public function addProjectReview($projectReview)
    {
        if (!$this->projectReviews->contains($projectReview)) {
            $this->projectReviews->add($projectReview);
            $projectReview->setProject($this);
        }

        return $this;
    }

    /**
     * @param ProjectReview $projectReview
     *
     * @return $this
     */
    public function removeProjectReview($projectReview)
    {
        if ($this->projectReviews->contains($projectReview)) {
            $this->projectReviews->removeElement($projectReview);
        }

        return $this;
    }

    /**
     * Get post id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get project categories.
     *
     * @return ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add category to project.
     *
     * @param Category $category Category entity
     */
    public function addCategory(Category $category)
    {
        $this->categories[] = $category;
    }

    /**
     * Remove category.
     *
     * @param Category $category Category
     *
     * @return $this
     */
    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * Set categories collection to project.
     *
     * @param ArrayCollection $categories Categories collection
     */
    public function setCategories(ArrayCollection $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Set project name.
     *
     * @param string $name A text of project name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get project name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set project slug.
     *
     * @param string $slug Unique text identifier
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get project slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set project description.
     *
     * @param string $description A text of description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get project description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set project url.
     *
     * @param string $url A url for project
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get project url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set date when project has been realized.
     *
     * @param \DateTime $date Date when project has been realized
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * Get date when project has been realized.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Get image filename.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set image and create thumbnail.
     *
     * @param string $image Full path to image file
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * Set time when project created.
     *
     * @param \DateTime $created A time when project created
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
    }

    /**
     * Get time when project created.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set time when project updated.
     *
     * @param \DateTime $updated A time when project updated
     */
    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;
    }

    /**
     * Get time when project updated.
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set project ordernum.
     *
     * @param int $ordernum
     */
    public function setOrdernum($ordernum)
    {
        $this->ordernum = $ordernum;
    }

    /**
     * Get project ordernum.
     *
     * @return int
     */
    public function getOrdernum()
    {
        return $this->ordernum;
    }

    /**
     * Set onFrontPage.
     *
     * @param bool $onFrontPage
     */
    public function setOnFrontPage($onFrontPage)
    {
        $this->onFrontPage = $onFrontPage;
    }

    /**
     * Get onFrontPage.
     *
     * @return bool
     */
    public function getOnFrontPage()
    {
        return $this->onFrontPage;
    }

    /**
     * Set imageFile.
     *
     * @param File $imageFile
     */
    public function setImageFile($imageFile)
    {
        $this->setUpdated(new \DateTime());
        $this->imageFile = $imageFile;
    }

    /**
     * Get imageFile.
     *
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param ArrayCollection $participants
     */
    public function setParticipants($participants)
    {
        $this->participants = $participants;
    }

    /**
     * @return ArrayCollection
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param User $participant
     */
    public function addParticipant(User $participant)
    {
        $this->participants->add($participant);
    }

    /**
     * @param User $participant
     */
    public function removeParticipant(User $participant)
    {
        $this->participants->removeElement($participant);
    }

    /**
     * @param ArrayCollection $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * @return ArrayCollection
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param Media $media
     */
    public function addMedia($media)
    {
        $this->media->add($media);
    }

    /**
     * @param Media $media
     */
    public function removeMedia($media)
    {
        $this->media->removeElement($media);
    }

    /**
     * @param string $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param bool $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @param bool $shadow
     */
    public function setShadow($shadow)
    {
        $this->shadow = $shadow;
    }

    /**
     * @return bool
     */
    public function getShadow()
    {
        return $this->shadow;
    }

    /**
     * @return bool
     */
    public function hasShadow()
    {
        return $this->shadow;
    }

    /**
     * @param ProjectTranslation $projectTranslation
     */
    public function addTranslations(ProjectTranslation $projectTranslation)
    {
        if (!$this->translations->contains($projectTranslation)) {
            $this->translations->add($projectTranslation);
            $projectTranslation->setObject($this);
        }
    }

    /**
     * @param ProjectTranslation $projectTranslation
     */
    public function addTranslation(ProjectTranslation $projectTranslation)
    {
        if (!$this->translations->contains($projectTranslation)) {
            $this->translations->add($projectTranslation);
            $projectTranslation->setObject($this);
        }
    }

    /**
     * @param ProjectTranslation $projectTranslation
     */
    public function removeTranslation(ProjectTranslation $projectTranslation)
    {
        $this->translations->removeElement($projectTranslation);
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
        $this->locale = $locale;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * @param string $metaKeywords
     *
     * @return Project
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaDescription
     *
     * @return Project
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * @return string
     */
    public function getCaseContent()
    {
        return $this->caseContent;
    }

    /**
     * @param string $caseContent
     *
     * @return Project
     */
    public function setCaseContent($caseContent)
    {
        $this->caseContent = $caseContent;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShowCase()
    {
        return $this->showCase;
    }

    /**
     * @param bool $showCase
     *
     * @return Project
     */
    public function setShowCase($showCase)
    {
        $this->showCase = $showCase;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRelativeProjects()
    {
        return $this->relativeProjects;
    }

    /**
     * @param mixed $relativeProjects
     *
     * @return Project
     */
    public function setRelativeProjects($relativeProjects)
    {
        $this->relativeProjects = $relativeProjects;

        return $this;
    }

    /**
     * @param Project $project
     */
    public function addRelativeProject(Project $project)
    {
        if (!$this->relativeProjects->contains($project)) {
            $this->relativeProjects->add($project);
        }
    }

    /**
     * @param Project $project
     */
    public function removeRelativeProject(Project $project)
    {
        if ($this->relativeProjects->contains($project)) {
            $this->relativeProjects->removeElement($project);
        }
    }

    /**
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * @param string $shortDescription
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * Get users with positions.
     *
     * @return Collection|UserWithPosition[] Users with positions
     */
    public function getUsersWithPositions()
    {
        return $this->usersWithPositions;
    }

    /**
     * Set users with positions.
     *
     * @param Collection|UserWithPosition[] $usersWithPositions Users with positions
     *
     * @return $this
     */
    public function setUsersWithPositions($usersWithPositions)
    {
        $this->usersWithPositions = $usersWithPositions;

        return $this;
    }

    /**
     * Remove user with position.
     *
     * @param UserWithPosition $userWithPosition User with position
     *
     * @return $this
     */
    public function removeUsersWithPositions(UserWithPosition $userWithPosition)
    {
        $this->usersWithPositions->removeElement($userWithPosition);

        return $this;
    }

    /**
     * Add user with position.
     *
     * @param UserWithPosition $userWithPosition User with position
     *
     * @return $this
     */
    public function addUsersWithPositions(UserWithPosition $userWithPosition)
    {
        $userWithPosition->setProject($this);

        $this->usersWithPositions->add($userWithPosition);

        return $this;
    }

    /**
     * @return string
     */
    public function getAdditionalInfo()
    {
        return $this->additionalInfo;
    }

    /**
     * @param string $additionalInfo
     */
    public function setAdditionalInfo($additionalInfo)
    {
        $this->additionalInfo = $additionalInfo;
    }
}
