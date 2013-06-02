food
====

A terribly simple app to fill a SelfOps need.

My trainer often asks me to write down what I eat. Historically, I kept that 
in a gmail draft, and sent it the day I was going - but I am forgetful and 
sometimes lazy. If I have a command line utility that I can just run next time
I am in a terminal (which is always) -- all the easier.

Also, this is far from perfect. But it Works For Me(tm).

Yeah, but why PHP and why Ruby?
-------------------------------
 * Because I wanted to
 * I wanted to write a client-side util in Ruby
 * I wanted to parse it with PHP
 * I know PHP is the opposite of cool, but its already set up and working on
   my server

Install
-------
If you really want to use this, make a table somewhere that has three columns,
`id`, `ts`, and `content`. 

 * `id` should be an `INT` and have `AUTO_INCREMENT` turned on.
 * `ts` should be a `TIMESTAMP`
 * `content` should be a `VARCHAR(128)`

Edit the variables in `eat.php` (server side) and `eat.rb` (client side) and off you go.

If you do use, this, well, that is cool. I would gladly accept any improvements :)

Using eat.rb
------------
* Add an entry: `./eat.rb -a yum food`
* Clear the database: `./eat.rb -c`

License
-------
Copyright (c) 2013 Patrick Cable (pc at p cable dot net)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
