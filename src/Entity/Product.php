<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @Vich\Uploadable()
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;


    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="product")
	 * @OrderBy({"id" = "DESC"})
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

	/**
     * @ORM\Column(type="string", length=100)
     */
	private $product;
	
	/**
     * @Vich\UploadableField(mapping="products", fileNameProperty="product")
     */
	private $productFile;
	
	/**
     * @ORM\Column(type="datetime")
     */
	private $update_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $short_content;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rating;


    public function __construct()
    {
		$this->update_at = new \DateTime("now");
        $this->comment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comment->contains($comment)) {
            $this->comment[] = $comment;
            $comment->setNo($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getNo() === $this) {
                $comment->setNo(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
	
	/**
     * @return mixed
     */
    public function getProductFile()
    {
        return $this->productFile;
    }
	
	/**
     * @param mixed thumbnailFile
     */
	public function setProductFile($productFile): void
    {
        $this->productFile = $productFile;
                           		
        if($productFile){
            $this->update_at = new \DateTime("now");
        }
	}
	
	/**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }
	
	/**
     * @param mixed product
     */
	public function setProduct($product): void
    {
        $this->product = $product;
    }

    public function getShortContent(): ?string
    {
        return $this->short_content;
    }

    public function setShortContent(string $short_content): self
    {
        $this->short_content = $short_content;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }
}
