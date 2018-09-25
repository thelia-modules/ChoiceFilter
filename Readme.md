# Choice Filter

This module allows the management of filters in front by template and category

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is ChoiceFilter.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/choice-filter-module:~0.1.0
```

## Usage

Explain here how to use your module, how to configure it, etc.

## Loop

[choice_filter]

### Input arguments

|Argument |Description |
|---      |--- |
|**template_id** | id of template |
|**category_id** | id of category |
|**order** | `position` or `position_reverse` |

### Output arguments

|Variable   |Description |
|---        |--- |
|**$TYPE**    | `feature` or `attribute` or other |
|**$ID**    | id of filter |
|**$POSITION**    | position of filter |
|**$VISIBLE**    | visible of filter |

### Exemple

#### For a template
```smarty
{loop name="choice_filter" type="choice_filter" template_id=$template_id}
    {if $TYPE == "feature" and $VISIBLE}
        {loop type="feature" name="feature-$ID" id=$ID}
            {* your code *}
        {/loop}
    {elseif $TYPE == "attribute" and $VISIBLE}
        {loop type="attribute" name="attribute-$ID" id=$ID}
            {* your code *}
        {/loop}
    {elseif $TYPE == "brand" and $VISIBLE}
        {* your code *}
    {elseif $TYPE == "price" and $VISIBLE}
        {* your code *}
    {/if}
{/loop}
```

#### For a category
```smarty
{loop name="choice_filter" type="choice_filter" category_id=$category_id}
    {if $TYPE == "feature" and $VISIBLE}
        {loop type="feature" name="feature-$ID" id=$ID}
            {* your code *}
        {/loop}
    {elseif $TYPE == "attribute" and $VISIBLE}
        {loop type="attribute" name="attribute-$ID" id=$ID}
            {* your code *}
        {/loop}
    {elseif $TYPE == "brand" and $VISIBLE}
        {* your code *}
    {elseif $TYPE == "price" and $VISIBLE}
        {* your code *}
    {/if}
{/loop}
```

for performance, it is best to use a cache block
http://doc.thelia.net/en/documentation/templates/smarty/cache.html

```smarty
{cache key="choice-filter" ttl=600 category_id==$category_id}
    {loop name="choice_filter" type="choice_filter" category_id=$category_id}
        {if $TYPE == "feature" and $VISIBLE}
            {loop type="feature" name="feature-$ID" id=$ID}
                {* your code *}
            {/loop}
        {elseif $TYPE == "attribute" and $VISIBLE}
            {loop type="attribute" name="attribute-$ID" id=$ID}
                {* your code *}
            {/loop}
        {elseif $TYPE == "brand" and $VISIBLE}
            {* your code *}
        {elseif $TYPE == "price" and $VISIBLE}
            {* your code *}
        {/if}
    {/loop}
{/cache}
```
