<?php

declare(strict_types=1);

/**
 * StofDoctrineExtensionsBundle — entity annotation/attribute examples.
 *
 * Shows how to use common Doctrine Extensions (Timestampable, Sluggable,
 * SoftDeleteable, Blameable) in Symfony entities.
 *
 * --- config/packages/stof_doctrine_extensions.yaml ---
 *
 * stof_doctrine_extensions:
 *     default_locale: en_US
 *     orm:
 *         default:
 *             timestampable: true
 *             sluggable: true
 *             softdeleteable: true
 *             blameable: true
 *
 * --- Example entity using PHP 8 attributes ---
 *
 * use Doctrine\ORM\Mapping as ORM;
 * use Gedmo\Mapping\Annotation as Gedmo;
 *
 * #[ORM\Entity]
 * #[Gedmo\SoftDeleteable(fieldName: 'deletedAt')]
 * class Article
 * {
 *     #[ORM\Id, ORM\GeneratedValue, ORM\Column]
 *     public int $id;
 *
 *     #[ORM\Column]
 *     public string $title;
 *
 *     #[Gedmo\Slug(fields: ['title'])]
 *     #[ORM\Column(unique: true)]
 *     public string $slug;
 *
 *     #[Gedmo\Timestampable(on: 'create')]
 *     #[ORM\Column]
 *     public \DateTimeImmutable $createdAt;
 *
 *     #[Gedmo\Timestampable(on: 'update')]
 *     #[ORM\Column]
 *     public \DateTimeImmutable $updatedAt;
 *
 *     #[ORM\Column(nullable: true)]
 *     public ?\DateTimeImmutable $deletedAt = null;
 *
 *     #[Gedmo\Blameable(on: 'create')]
 *     #[ORM\Column(nullable: true)]
 *     public ?string $createdBy = null;
 * }
 *
 * --- Usage: slug and timestamps are set automatically ---
 *
 * $article = new Article();
 * $article->title = 'Hello World';
 * $entityManager->persist($article);
 * $entityManager->flush();
 * // $article->slug === 'hello-world'
 * // $article->createdAt is set automatically
 * // $article->createdBy is set to the current user's username
 *
 * --- Soft delete: filter deleted records automatically ---
 *
 * // Requires SoftDeleteable filter to be enabled in config
 * $repo->findAll(); // deleted records excluded
 * $entityManager->remove($article);
 * $entityManager->flush();
 * // $article->deletedAt is set; record is NOT removed from database
 */

echo 'StofDoctrineExtensionsBundle requires a Symfony kernel with Doctrine.' . PHP_EOL;
echo 'See the docblock above for entity annotation patterns.' . PHP_EOL;
