# Feeds Tamper custom plugins
Provides a Feeds Tamper custom plugins for legacy imports (D7 to D9).

## The problem

We need to migrate a node type from D7 to D9 but many options in many fields have changed.
Some options have disappeared. Multiple options are now represented by a single option in some cases.
The D7 options keys are textual|textual but in D9 they are integer|textual.

## The solution

Create a custom tamper plugin that the feeds module can use to re-write incoming values from a .csv exported from D7.
This contains various mapping arrays D7 value => D9 value eg

 $map = array(
    '0-28 days' => '1',
    '1-12 months' => '1',
    '1-15 years' => '2',
    '16-17 years' => '3',
    '18-24 years' => '4',
    '25-64 years' => '5',
    '65-85 years' => '7',
    '85 +' => '8',  etc..

This extension has been developed and by [Michael Chaplin](https://github.com/mikantchap).
