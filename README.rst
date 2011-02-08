=====
Phake
=====

Phake, from "PHP + Rake," is sort of Rake + ActiveRecord::Migrations
implemented in PHP. I had two goals with the project:

* Fully unit tested and test-first when possible.
* A SQL-dialect agnostic API.

You can see from some of the `migrations <migrations>`_ the general sketch of
the API. In the `sqls <sqls>`_ directory you can see the SQL generator at the
heart of Phake. Currently only MySQL is mostly implemented.


TODO
====

* Finish MySQL generator.
* Write SQLite generator.
* Write PostgreSQL generator.
