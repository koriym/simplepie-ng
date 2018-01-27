# General Usage (Draft)

## Fetching Feeds Over HTTP

TBD.

## Caching Data

TBD.

## Working with `Node` types

With few exceptions, every value that comes out of SimplePie is a `Node` type.

One of the complexities of working with [Atom] feeds (and sometimes [RSS] feeds) is that the content you're looking for may be serialized as `text`, `html`, or `xhtml`. This was something that we didn't solve very well in SimplePie OG, so it's something that I've paid better attention to in SimplePie NG by exposing these serializations.

Let's say that you want to get the author of a feed.

```php
$author = $feed->getAuthor();
```

There are a few things that we do with this. If we were to type-cast it as a _string_, then it would trigger the `__toString()` function on the class.

```php
echo (string) $author . PHP_EOL;
#=> Ryan Parman <http://ryanparman.com>
```

However, maybe we want to display the author's information differently. We know that `$author` is a type of `Person`.

```php
echo get_class($author) . PHP_EOL;
#=> SimplePie\Type\Person
```

`Person` has three methods: `getName()`, `getUrl()`, and `getEmail()`.

```php
echo sprintf(
  "%s\ne: %s\nw: %s",
  (string) $author->getName(),
  (string) $author->getEmail(),
  (string) $author->getUrl()
) . PHP_EOL;
#=> Ryan Parman
#=> e: ryan@ryanparman.com
#=> w: http://ryanparman.com
```

If you were to check the types of `getName()`, `getUrl()`, and `getEmail()`, you'll see that they're all `Node` types.

```php
echo get_class($author->getName()) . PHP_EOL;
#=> SimplePie\Type\Node
```

The `Node` type's `__toString()` method will call out to its `getValue()` method to stringify the value.

```php
echo (string) $author->getName() . PHP_EOL;
#=> Ryan Parman
```

The `Node` type itself has a few methods: `getValue()`, `getSerialization()`, and `getNode()`.

`getValue()` is where the actual value itself is stored -- it simply gets bubbled up by higher-level calls. It's the first time that a value doesn't need to be type-cast as a _string_.

```php
echo $author->getName()->getValue() . PHP_EOL;
#=> Ryan Parman
```

`getSerialization()` is how we can tell which serialization (`text`, `html`, or `xhtml`) the content inside the feed is using.

```php
echo $author->getName()->getSerialization() . PHP_EOL;
#=> text

switch ($author->getName()->getSerialization()) {
    case 'xhtml':
        // Custom XML stuff...
        break;
    case 'text':
    case 'html':
    default:
        echo $author->getName()->getValue();
}
#=> Ryan Parman
```

Lastly, `getNode()` returns the low-level `DOMNode` element, just in case you wanted to access the data that the parser has directly.

## Working with `DateTime` types

TBD.

.. reviewer-meta::
   :written-on: 2017-12-17
   :proofread-on: 2017-12-17

  [Atom]: https://tools.ietf.org/html/rfc4287
  [RSS]: http://www.rssboard.org/rss-specification