<?php declare(strict_types=1);

namespace App\Domain;

use App\Collection\Collection;
use Doctrine\Common\Collections\Criteria;

/**
 * This is a Collection of PathPart, which we can name a "Path" (beware: it is not a Collection of "Paths").
 *
 * @method static __construct(PathPart[] $elements)
 * @method PathPart[] toArray()
 * @method PathPart first()
 * @method PathPart last()
 * @method PathPart next()
 * @method PathPart current()
 * @method bool removeElement(PathPart $element)
 * @method bool contains(PathPart $element)
 * @method mixed indexOf(PathPart $element)
 * @method PathPart[] getValues()
 * @method void set($key, PathPart $element)
 * @method bool add(PathPart $element)
 * @method \ArrayIterator|PathPart[] getIterator()
 * @method PathPart[]|Path map(\Closure $p)
 * @method PathPart[]|Path filter(\Closure $p)
 * @method Path[] partition(\Closure $p)
 * @method PathPart[]|Path matching(Criteria $criteria)
 */
final class Path extends Collection
{
    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return implode(
            DIRECTORY_SEPARATOR,
            $this->matching(Criteria::create()->orderBy(array('priority' => Criteria::ASC)))
                ->filter(function (PathPart $pathPart) {
                    return !empty($pathPart->getPath());
                })
                ->map(function (PathPart $pathPart) {
                    return $pathPart->getPath();
                })
                ->toArray()
        );
    }

    /**
     * @param Path $path
     *
     * @return Path
     */
    public static function createFromPath(Path $path): Path
    {
        return clone $path;
    }
}
