<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Cet adresse mail existe déjà!")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user", "comment", "post"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Groups({"user", "comment"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Groups({"user"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=200, unique=true)
     * @Groups({"user", "comment","post"})
     */
    private $nickname;

    /**
     * @ORM\Column(type="string", length=200, unique=true)
     * @Groups({"user", "post"})
     */
    private $email;

    /**
     * @Groups({"post"})
     * @ORM\Column(type="json_array")

     */
    private $roles = array();

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", length=200)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Groups({"user", "comment"})
     */
    private $picture;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * 
     */
    private $themedisplay;

    /**
     * @ORM\Column(type="datetime", columnDefinition="timestamp default current_timestamp")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true, columnDefinition="timestamp default current_timestamp on update current_timestamp")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="user")
     * @Groups({"user"})
     * 
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user")
     * @Groups({"user"})
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $comment;

    /**
     * @ORM\ManyToMany(targetEntity=Post::class, mappedBy="userLike")
     * @Groups({"user"})
     * 
     */
    private $likedPosts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->comment = new ArrayCollection();
        $this->likedPosts = new ArrayCollection();
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername()
    {
        return $this->email;
    }

    public function getUserIdentifier() 
    {
        return $this->email;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * $user->setRoles['ROLE_ADMIN', 'ROLE_MODO']
     *
     * @param array $roles
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getThemedisplay(): ?bool
    {
        return $this->themedisplay;
    }

    public function setThemedisplay(bool $themedisplay): self
    {
        $this->themedisplay = $themedisplay;

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

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getLikedPosts(): Collection
    {
        return $this->likedPosts;
    }

    public function addLikedPost(Post $likedPost): self
    {
        if (!$this->likedPosts->contains($likedPost)) {
            $this->likedPosts[] = $likedPost;
            $likedPost->addUserLike($this);
        }

        return $this;
    }

    public function removeLikedPost(Post $likedPost): self
    {
        if ($this->likedPosts->removeElement($likedPost)) {
            $likedPost->removeUserLike($this);
        }

        return $this;
    }
}
