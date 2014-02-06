Bartering Exchange System
==============

The Bartering Exchange System is a large-scale team project for a class that was designed to teach students about software
engineering and diagramming. The entirety of the back-end code is written in PHP while the front-end/graphical side is
written in Flash. The Bartering Exchange System is similar to a system like Craigslist, but with trading. Users are able
to create accounts and then put up an item that they have and pair it with an item that they want. Ex: User 1 puts up a pencil
and wishes to trade it for a pen. This is stored in a database as a pair. The system is then allowed to create as long
of a chain of trades as possible. For simplicity, the trade limit is capped at 10. Eventually the original person will get what
they want, but there may have been many trades involved along the way.

Ex:
- User 1 has pencil, wants pen
- User 2 has pen, wants eraser
- User 3 has eraser, wants pencil

When the system finds all three of these users, it will create a chain of trades where everybody gets what they want. When
a trade is created, all of the users/items involved are taken out of the database and stored in a second database until trades are
finalized. All of the users are notified of the trade and are allowed to confirm or deny the trade. If anyone denies the trade, all of
the users are placed back into the original database until another match is found.

As part of the project users are also able to change their login credentials and a timeout feature exists so that any user who has not had
any activity within 15 minutes is automatically logged out.
