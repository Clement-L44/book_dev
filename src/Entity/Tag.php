<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Article>
     */
    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'tags')]
    private Collection $articles;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'childTag')]
    private ?self $parentTag = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parentTag')]
    private Collection $childTag;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->childTag = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->addTag($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): static
    {
        if ($this->articles->removeElement($article)) {
            $article->removeTag($this);
        }

        return $this;
    }

    public function getParentTag(): ?self
    {
        return $this->parentTag;
    }

    public function setParentTag(?self $parentTag): static
    {
        $this->parentTag = $parentTag;

        return $this;
    }

    public function getChildTag(): Collection
    {
        return $this->childTag;
    }

    public function addChildTag(self $childTag): static
    {
        if (!$this->childTag->contains($childTag)) {
            $this->childTag->add($childTag);
            $childTag->setParentTag($this);
        }

        return $this;
    }

    public function removeChildTag(self $childTag): static
    {
        if ($this->childTag->removeElement($childTag)) {
            // set the owning side to null (unless already changed)
            if ($childTag->getParentTag() === $this) {
                $childTag->setParentTag(null);
            }
        }

        return $this;
    }
}
