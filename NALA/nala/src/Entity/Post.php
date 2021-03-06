<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user", "comment", "post", "category"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     * @Groups({"user", "comment", "post", "category"})
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user", "comment", "post", "category"})
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"post"})
     */
    private $display;

    /**
     * @ORM\Column(type="datetime", columnDefinition="timestamp default current_timestamp")
     * @Groups({"post", "category"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true, columnDefinition="timestamp default current_timestamp on update current_timestamp")
     * @Groups({"post", "category"})
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"post"})
     * cascade={"persist"}
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="posts")
     * @Groups({"post","user"})
     * 
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post",orphanRemoval=true)
     * cascade={"persist"}
     * @Groups({"post"})
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $comment;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="likedPosts", orphanRemoval=true)
     * @Groups({"post"})
     * 
     */
    private $userLike;
    
     /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $pictureBase64;


    

    public function __construct()
    {
        $this->likeUser = new ArrayCollection();
        $this->comment = new ArrayCollection();
        $this->userLike = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
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

    public function getDisplay(): ?bool
    {
        return $this->display;
    }

    public function setDisplay(bool $display): self
    {
        $this->display = $display;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserLike(): Collection
    {
        return $this->userLike;
    }

    public function addUserLike(User $userLike): self
    {
        if (!$this->userLike->contains($userLike)) {
            $this->userLike[] = $userLike;
        }

        return $this;
    }

    public function removeUserLike(User $userLike): self
    {
        $this->userLike->removeElement($userLike);

        return $this;
    }

    public function getPictureBase64(): ?string
    {
        return $this->pictureBase64;
    }

    public function setPictureBase64(?string $pictureBase64): self
    {
        $this->pictureBase64 = $pictureBase64;

        return $this;
    }

   
}
