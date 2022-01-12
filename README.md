# Stencil Block Class

This is a class designed specifically for implementing a StencilJS web component into 
a Gutenberg block. As WordPress's reliance on Gutenberg grows, and headless WordPress
continues to grow in popularity, the workflow of building web components as standalone
frontend components that work with or without the WordPress frontend system makes 
a lot of sense. 

## Proposed workflow
Design interface as it would exist in the block editor. 
IF the design for the content as it would appear on the frontend and how
it should look and behave in the editor are the same, then it starts to
make a lot of sense to build the component as a web component. This way, 
all styling and javascript can be developed standalone. Then, implementation
in Gutenberg becomes extremely easy with less code living in the block and
more code living in the component. This is workflow becomes more ideal the more
complex the UX of the component is. 

## Implementation
Once your component is developed, simply use the npx-create-block package to
generate your block. Then, add this class into your block code at the root 
level of the plugin and require it in your plugin root php file. 

`
require_once 'class-StencilBlock.php';
`

`$my_block = new StencilBlock('custom-component', 'custom-component', 'components/build/');`

## Params
The first argument for the constructor is the handle for the component script.

The Second argument for the constructor is the filename of the component script without the ".js" on the end.
This class assumes that the .esm and the regular .js files for the component are located adjacent to one another. 
So, there is no need to supply the esm script, as that is taken care of by the class.

The third argument (optional) is for the relative path for the plugin to find the component build files
in your plugin directory structure. If no relative path is provided, then the class assumes 
that the filename includes the entire url to the file. This is to account for scenarios where
the component scripts are hosted outside of the plugin (like a CDN perhaps).