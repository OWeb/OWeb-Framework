<?php
/**
 * @author       Oliver de Cramer (oliverde8 at gmail.com)
 * @copyright    GNU GENERAL PUBLIC LICENSE
 *                     Version 3, 29 June 2007
 *
 * PHP version 5.3 and above
 *
 * LICENSE: This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see {http://www.gnu.org/licenses/}.
 */

namespace Model\articles;

use OWeb\utils\Singleton;

/**
 * Description of Artciles
 *
 * @author De Cramer Oliver
 */
class Artciles extends Singleton
{

    private $ext_connection;
    private $categories;

    private $articles = array();


    static public function getInstance()
    {
        $class = self::getInstanceNull();
        if ($class == null) {
            $obj = new Artciles(Categories::getInstance());
            self::setInstance($obj);

            return $obj;
        }
    }

    function __construct(Categories $cat)
    {
        $this->ext_connection = \OWeb\manage\Extensions::getInstance()->getExtension('db\Connection');
        $this->categories     = $cat;
    }

    public function getArticle($id)
    {

        if (isset($this->articles[$id]))
            return $this->articles[$id];
        else {

            try {
                $connection = $this->ext_connection->get_Connection();
                $prefix     = $this->ext_connection->get_prefix();

                $sql = $connection->prepare(
                    "SELECT *
                                                                    FROM " . $prefix . "article_article aa, " . $prefix . "article_content ac
						WHERE ac.article_id = id_article 
						AND id_article = :id"
                );

                if ($sql->execute(array(':id' => $id)) && $result = $sql->fetchObject()) {

                    $article             =
                        new \Model\articles\Article        (
                            $result->id_article,
                            $result->type,
                            $result->img,
                            $result->published == 1,
                            $result->pdate,
                            $this->categories->getElement($result->category_id)
                        );
                    $this->articles[$id] = $article;

                    $article->addLanguage($result->article_lang, $result->title, $result->content);
                    while ($result = $sql->fetchObject()) {
                        $article->addLanguage($result->article_lang, $result->title, $result->content);
                    }

                    $sql = "SELECT * FROM " . $prefix . "article_category_more
								WHERE article_id = $id";
                    if ($sql = $connection->query($sql)) {
                        while ($result = $sql->fetchObject()) {
                            $article->addCategorie($this->categories->getElement($result->category_id));
                        }
                    }
                    $sql = "SELECT * FROM " . $prefix . "article_attribute
								WHERE article_id = $id";
                    if ($sql = $connection->query($sql)) {
                        while ($result = $sql->fetchObject()) {
                            $article->addAttribute($result->name, $result->value);
                        }
                    }
                    $article->setisDOne(true);

                    return $article;
                } else {
                    throw new \Model\articles\exception\ArticleNotFound("Couldn't get Article with id : $id . SQL ERROR2", 0);
                }

            } catch (\Exception $ex) {
                throw new \Model\articles\exception\ArticleNotFound("Couldn't get Article with id : $id . SQL ERROR", 0, $ex);
            }
        }
    }

    public function getNbCategoryArticles(\Model\articles\CategorieElement $cat, $rec = true, $pusblished = true)
    {

        try {
            $connection = $this->ext_connection->get_Connection();
            $prefix     = $this->ext_connection->get_prefix();

            $cs = $cat->getChildrens();
            if ($rec && !empty($cs)) {
                $cat_parents = $cat->getRecuriveChildrensIds();

                $sql = "SELECT COUNT(*) as nb
						FROM " . $prefix . "article_article aa
						WHERE (id_article IN (SELECT acm.article_id 
														FROM " . $prefix . "article_category_more acm 
														WHERE acm.category_id = " . $cat->getId(
                ) . " )
								OR category_id = " . $cat->getId() . "
								OR id_article IN (SELECT acm.article_id 
														FROM " . $prefix . "article_category_more acm 
														WHERE acm.category_id IN ($cat_parents))
								OR category_id IN ($cat_parents))"
                . " AND published = " . $pusblished ? '1' : '0';
                //return;
            } else
                $sql = "SELECT COUNT(*) as nb
						FROM " . $prefix . "article_article aa
						WHERE (id_article IN (SELECT acm.article_id 
													FROM " . $prefix . "article_category_more acm 
													WHERE acm.category_id = " . $cat->getId(
                ) . " )
							OR category_id = " . $cat->getId() . ")"
                . " AND published = " . $pusblished ? '1' : '0';


            if ($sql = $connection->query($sql)) {

                if ($result = $sql->fetchObject()) {
                    return $result->nb;
                }
            }
        } catch (\Exception $ex) {
            throw new \Model\articles\exception\ArticleNotFound("Couldn't get Nb Articles of Category : " . $cat->getId(
            ) . " . SQL ERROR", 0, $ex);
        }
    }

    public function getCategoryArticles(
        \Model\articles\CategorieElement $cat, $start, $nbElement, $rec = true, $pusblished = true
    ) {

        try {
            $connection = $this->ext_connection->get_Connection();
            $prefix     = $this->ext_connection->get_prefix();

            $cs = $cat->getChildrens();
            if ($rec && !empty($cs)) {
                $cat_parents = $cat->getRecuriveChildrensIds();

                $sql2 = "SELECT id_article
						FROM " . $prefix . "article_article aa
						WHERE (id_article IN (SELECT acm.article_id 
														FROM " . $prefix . "article_category_more acm 
														WHERE acm.category_id = " . $cat->getId(
                    ) . " )
								OR category_id = " . $cat->getId() . "
								OR id_article IN (SELECT acm.article_id 
														FROM " . $prefix . "article_category_more acm 
														WHERE acm.category_id IN ($cat_parents))
								OR category_id IN ($cat_parents))"
                    . " AND published = " . ($pusblished ? '1' : '0') . "
						ORDER BY pdate DESC
						LIMIT $start, $nbElement";
                //return;
            } else
                $sql2 = "SELECT id_article
						FROM " . $prefix . "article_article aa
						WHERE (id_article IN (SELECT acm.article_id 
													FROM " . $prefix . "article_category_more acm 
													WHERE acm.category_id = " . $cat->getId(
                    ) . " )
							OR category_id = " . $cat->getId()
                    . ") AND published = " . ($pusblished ? '1' : '0') . "
						ORDER BY pdate DESC
						LIMIT $start, $nbElement";

            //$sql = $connection->prepare($sql);
            $article_ids = "";

            if ($sql = $connection->query($sql2)) {
                while ($result = $sql->fetchObject()) {
                    $article_ids .= $result->id_article . " ,";
                }

                $article_ids = substr($article_ids, 0, strlen($article_ids) - 1);
            }

            if ($article_ids == "")
                return array();

            $sql2 = "SELECT *
						FROM " . $prefix . "article_article aa, " . $prefix . "article_content ac
						WHERE ac.article_id = id_article 
							AND id_article IN ($article_ids)
					ORDER BY pdate DESC";

            if ($sql = $connection->query($sql2)) {

                $articles = array();
                while ($result = $sql->fetchObject()) {
                    if (isset($this->articles[$result->id_article]) && !isset($articles[$result->id_article]))
                        $articles[$result->id_article] = $this->articles[$result->id_article];

                    else if (!isset($articles[$result->id_article])) {
                        $articles[$result->id_article] =
                            new \Model\articles\Article        (
                                $result->id_article,
                                $result->type,
                                $result->img,
                                $result->published == 1,
                                $result->pdate,
                                $this->categories->getElement($result->category_id)
                            );

                        $this->articles[$result->id_article] = $articles[$result->id_article];
                    }
                    if (!$articles[$result->id_article]->isDone()) {

                        $articles[$result->id_article]->addLanguage(
                            $result->article_lang,
                            $result->title,
                            $result->content
                        );
                    }
                }

                $sql2 = "SELECT * FROM " . $prefix . "article_category_more
								WHERE article_id IN ( $article_ids) ";
                if ($sql = $connection->query($sql2)) {
                    while ($result = $sql->fetchObject()) {
                        if (!$articles[$result->article_id]->isDone())
                            $articles[$result->article_id]->addCategorie(
                                $this->categories->getElement($result->category_id)
                            );
                    }
                }

                $sql2 = "SELECT * FROM " . $prefix . "article_attribute
								WHERE article_id IN ( $article_ids) ";
                if ($sql = $connection->query($sql2)) {
                    while ($result = $sql->fetchObject()) {
                        if (!$articles[$result->article_id]->isDone())
                            $articles[$result->article_id]->addAttribute($result->name, $result->value);
                    }
                }

                if (is_array($articles))
                    foreach ($articles as $article)
                        $article->setisDOne(true);

                return $articles;
            } else {
                throw new \Model\articles\exception\ArticleNotFound("Couldn't get Articles of Category : " . $cat->getId(
                ) . " . SQL ERROR2" . $sql2, 0);
            }

        } catch (\Exception $ex) {
            throw new \Model\articles\exception\ArticleNotFound("Couldn't get Articles of Category : " . $cat->getId(
            ) . " . SQL ERROR", 0, $ex);
        }

    }

    public function getArticleByNameCategory($name, \Model\articles\CategorieElement $cat)
    {
        $connection = $this->ext_connection->get_Connection();
        $prefix     = $this->ext_connection->get_prefix();

        $sql = "SELECT *
				FROM " . $prefix . "article_article aa, " . $prefix . "article_content ac
				WHERE ac.article_id = id_article 
				AND ac.title = " . $connection->quote($name) . "
				AND (aa.category_id = " . $cat->getId() . "
					OR id_article IN (SELECT acm.article_id 
													FROM " . $prefix . "article_category_more acm 
													WHERE acm.category_id = " . $cat->getId(
            ) . " ))";

        if ($sql = $connection->query($sql)) {
            $result                              = $sql->fetchObject();
            $article                             =
                new \Model\articles\Article        (
                    $result->id_article,
                    $result->type,
                    $result->img,
                    $result->published == 1,
                    $result->pdate,
                    $this->categories->getElement($result->category_id)
                );
            $this->articles[$result->id_article] = $article;
            $id                                  = $result->id_article;

            $article->addLanguage($result->article_lang, $result->title, $result->content);
            while ($result = $sql->fetchObject()) {
                $article->addLanguage($result->article_lang, $result->title, $result->content);
            }

            $sql = "SELECT * FROM " . $prefix . "article_category_more
						WHERE article_id = $id";
            if ($sql = $connection->query($sql)) {
                while ($result = $sql->fetchObject()) {
                    $article->addCategorie($this->categories->getElement($result->category_id));
                }
            }
            $sql = "SELECT * FROM " . $prefix . "article_attribute
						WHERE article_id = $id";
            if ($sql = $connection->query($sql)) {
                while ($result = $sql->fetchObject()) {
                    $article->addAttribute($result->name, $result->value);
                }
            }
            $article->setisDOne(true);

            return $article;
        }


    }

}

?>
