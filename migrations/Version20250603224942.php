<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250603224942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE course (id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', title VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE lesson (id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', course_id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', title VARCHAR(100) NOT NULL, content LONGTEXT DEFAULT NULL, position INT NOT NULL, INDEX IDX_F87474F3591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE progress (id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', user_id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', lesson_id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', completed_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_2201F246A76ED395 (user_id), INDEX IDX_2201F246CDF80196 (lesson_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)', name VARCHAR(100) NOT NULL, email VARCHAR(30) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lesson ADD CONSTRAINT FK_F87474F3591CC992 FOREIGN KEY (course_id) REFERENCES course (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE progress ADD CONSTRAINT FK_2201F246A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE progress ADD CONSTRAINT FK_2201F246CDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F3591CC992
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE progress DROP FOREIGN KEY FK_2201F246A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE progress DROP FOREIGN KEY FK_2201F246CDF80196
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE course
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE lesson
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE progress
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
    }
}
