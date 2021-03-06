# Edison #

Heavily inspired by Github's [Scientist](https://github.com/github/scientist)
library, Edison provides a flexible infrastructure for refactoring code with
confidence.

Edison will allow you to run "experiments" in production, pitting a "control" code
path against a "variant" and logging or alerting when the results are not
identical. Edison is not opinionated; you can provide your own implementations
for comparing the results of the operations performed and for logging or
alerting when things happen.

> "I speak without exaggeration when I say that I have constructed three thousand
> different theories in connection with the electric light, each one of them
> reasonable and apparently to be true.  Yet only in two cases did my
> experiments prove the truth of my theory."
>
> Thomas Edison, in an interview in Harper's Monthly Magazine, 1890.

## Conducting an Experiment ##

Conducting an experiment is simple. Give Edison two code paths to evaluate and
a journal object to persist the results somewhere and you're off to the races.

```php
<?php
use \AaronBieber\Edison\Experiment;
use \AaronBieber\Edison\Echo_Journal;

$experiment = new Experiment(new Echo_Journal('test-some-refactor'));
$experiment = $experiment
    ->use_control(function () { /* Control code */ })
    ->use_variant(function () { /* Variant (test) code */ });

$result = $experiment->run();
```

By default, Edison will run both the control and variant code every time,
comparing the results of the two functions using `==`. The results of the
experiment will be handed off to the journal's `save()` method, so you can set
it up to do anything you want. In this example, we're using a journal that ships
with Edison that simply prints the results as a JSON string.

The results will include the full return values of the control and the variant,
the execution time in milliseconds, and whether the results were found to be
equivalent by Edison.

If your refactored code is potentially slower, or you don't have a great deal of
confidence in it (which is why you're running an experiment, right?) you can
tell Edison to run it much less often. Call `variant_percent()` on the
experiment and give it an integer percentage for how often the variant should be
tested.

Experimental data is only generated when the test runs, so this will also help
to keep logging volume down if you're refactoring a heavily trafficked code
path.

## Analyzing the Results ##

Of course, in large environments, it makes sense to send the experiment results
to some other service, like your ELK (Elasticsearch, Logstash, Kibana) stack,
Redis, etc. To do this, Edison allows you to provide your own experimental
journal implementation.

Simply create an object that implements `\AaronBieber\Edison\Interfaces\Journal`
and pass it into the `\AaronBieber\Edison\Experiment` constructor. The journal's
`save()` method will be called with the results of the experiment stored in an
instance of `\AaronBieber\Edison\Observation`. Handle the results however you
want, perhaps sending timing data to Graphite and the return values to Logstash
or Redis.

## Sophisticated Comparison ##

Depending on what you're trying to refactor, the results of the original and
refactored code paths may not be comparable using `==`. In that case, you can
also provide Edison with your own comparator that implements
`\AaronBieber\Edison\Interfaces\Comparator`.

This allows you to explicitly define the success criteria for your
experiments. Perhaps both code paths produce an object containing the same data,
but the new object has a different structure. You could create your own
`Comparator` class that compares the values within the objects and returns true
only if all of them are the same.

```php
<?php
use AaronBieber\Edison\Experiment;
use AaronBieber\Edison\Echo_Journal;

$experiment = new Experiment(
    new Echo_Journal('test-some-refactor'),
    new Custom_Comparator()
);

$experiment = $experiment
    ->variant_percent(50)
    ->use_control(function () { /* Control code */ })
    ->use_variant(function () { /* Variant (test) code */ });

$result = $experiment->run();
```

## Work in Progress ##

This project is essentially a beta; I expect to refine it over time. If you
actually use it and run into problems, open issues or, better yet, send me pull
requests!

## License ##

This software is distributed under the terms of the MIT License.

The MIT License (MIT)

Copyright (c) 2016 Aaron Bieber

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
