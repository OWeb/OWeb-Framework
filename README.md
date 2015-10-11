OWeb-Framework
==============

Here is yet another framework, I want to believe it is better then Symfony and other frameworks around but well it ain't finished and probably will never realy bee. 

The reason why I have been working on this isn't to make a website, the few websites I made (beside my own, oo there is tm Teams) doesent use my own framework but Symfony and also some Zend on my earlier days. I have also worked on bigger projects using Magento and Drupal. 

I love the way Magento handles it's templates, layouts and blocks even throught I find it a little complicated. Symfony does a better job at it but sadly it looses native block cache support which is quite important in some situations.
I love the way RBS-Change handles (handled) it's database.
I love the way assetics work in smyfony, so much better then to have parts of modules in different directories like Magento; or have the modules directory in the public section of the web server. 
And I love the way Drupal handles ooo, translations. Oo noo requires 50 modules to do that. Hmmm full page cache? that is useless, better to use varnish. ooo well nothing I don't like drupal, drupal 7 at least. 

Putting all of this together you can have an idea of what OWeb is about. But on all of this cases we have heavy systems loading useless stuff on every page call. There comes OWeb. OWeb core is useless, it can't even display a single page, actually the core can be used for coding a cli-tool or even a deamon (Maniaplanet project eXpansion is a deamon in some ways)
By default OWeb loads a few classes and that is that, you can't make it lighter. 

On top of this we will add a extensions and that is the life of OWeb. But first what is the difference between a module and and extension. 

In a core OWeb, a module contains models and extensions. For oweb a module is just a container, OWeb dont' need to know the list of modules. The only thing oweb needs to know is the list of modules it needs to start with (By default oweb will start a logging extension (Contained in the log module)). 

So oweb comes with a command client thanks to the console extension. This allows well to have some commands to do some things, so much wooow, like Symfony, it also comes with an publicAssets module that adds commands to that console to create symlinks and such. So you need to tell oweb which eXtension to load for the console in the configuration of the console module. You can also configure a global list that will always be loaded. 

It also comes with a frontController extension that will add controllers & blocks and templates and theme support to OWeb. Once more you can define extensions to be loaded only for the front controller. 

We can add many distinct features this way to OWeb or change completely the way it works. But globally we should continue considering we have only those 2 distinctive elements. As you can guess of course you shouldn't load the console & front controller extensions together. 

So back to what is an extension, and extension has 2 roles, one is global the second is localized. The database connection extension for example is local, it doesen't need to be loaded on all pages but just when needed, the same way as publicAssets, if a page has none you won't need to load it. You just need to add dependencies to your pages for oweb to load it just when you need it. 

The global extensions are extension that can affect all pages. So for example you wish to create an analytics tool that stores the execution time of all pages, well you can do an extension and then set the configuration for it to be loaded on all pages. 

Basically OWeb is a lazy Framework. 

OWeb offers also a unique solution for extension, the best example of this is for caches. OWeb comes with a uselss cache integration. Basically everytime you setCache it returns true and everytime you do getCache it returns null. All components supporting caching depends on this extension. 

But what happens then when you really want to cache ? Well you simple say to OWeb to load the RedisCache extension and voila some magic. 
RedisCache extension extends the DummyCache extension that all the pages were depending on, so when it was loaded it replaced it's small brother on all fronts. You can replace this cache extension by any other implementation you want without affecting thousands of lines of codes. Isn't that beatifull ? 
Using this technique you can replace anything from the frontController to the logger or to the settings handler. 

The only drawback of this is the necessity for loading the classes of the extension even if it isn't needed on all pages. It needs the class to find all parents and build the replacement tree. But it is still better then loading all the extensions. (Pay attention I just loaded the class, the file. I haven't started the extension. With OPC this should create no hassle unless you have a hundreds of extension).  

**The text aboce isn't 100% true as the core is still being developped**

## Getting Started ##

**OWeb installation is not possible at the moment, developpments are in progress**

OWeb will be installed with composer. The default files will look like this

* config
  * main.yml
  * console.yml
  * web.yml
* vendor
* www
  * src
    * css
    * js
    * ...
  * index.php

## OWeb commands ##

You can use the fallowing commands to get the list of available commands : 

`vendor/bin/oweb oweb:cmd:list`
Will give you list of all available commands

`vendor/bin/oweb oweb:cmd:help --name="oweb:cmd:list"`
will show you help about how to use a command

### Adding Commands ###
Commands are added in extensions, you will need to add the extension in the console.yml file. 
