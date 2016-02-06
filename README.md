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

Conducting an experiment is simple. Give Edison an experiment name and two code
paths to evaluate and you're off to the races.

```php
<?php
$experiment = new Edison\Experiment('test-some-refactor');
$experiment = $experiment->variant_percent(50)
    ->use_control(function () { /* Control code */ })
    ->use_variant(function () { /* Variant (test) code */ });

$result = $experiment->run();
```

By default, Edison will run both the control and variant code every time,
comparing the results of the two functions using `==`. The results of the
experiment will be written to a log file in the `/tmp` directory using a
filename derived from the experiment name. The result from the control is always
returned.

If your refactored code is potentially slower, or you don't have a great deal of
confidence in it (which is why you're running an experiment, right?) you can
tell Edison to run it much less often. Call `variant_percentage()` on the
experiment and give it an integer percentage for how often the variant should be
tested.

Experimental data is only generated when the test runs, so this will also help
to keep logging volume down if you're refactoring a heavily trafficked code
path.

## Better Logging ##

Of course, in large environments, it makes sense to send the experiment results
to some other service, like your ELK (Elasticsearch, Logstash, Kibana) stack,
Redis, etc. To do this, Edison allows you to provide your own experimental
journal implementation.

Simply create an object that implements `Edison\Journal` and pass it into the
`Edison\Experiment` constructor. Edison ships with a toy journal implementation
called `Echo_Journal` that simply spits out a JSON representation of experiment
results using `echo`, which can be helpful for debugging or as a model for your
own journal.

## Sophisticated Comparison ##

Depending on what you're trying to refactor, the results of the original and
refactored code paths may not be comparable using `==`. In that case, you can
also provide Edison with your own comparator that implements
`Interfaces\Comparator`.

This allows you to explicitly define the success criteria for your
experiments. Perhaps both code paths produce an object containing the same data,
but the new object has a different structure. You could create your own
`Comparator` class that compares the values within the objects and returns true
only if all of them are the same.
