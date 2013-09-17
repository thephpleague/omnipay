# Wirecard

## Notes

To test locally, if you have composer.phar in the root
directory (omnipay):

php composer.phar update

and then run a local php server (see notes in root directory)

It's pretty cool.


# Some info

Amount: 100002 - 02 Call Voice Authorization Number. 

Amount: 100003 - 03 Invalid Merchant Number. 

Amount: 100004 - 04 Retain Card.

Amount: 100005 - 05 Authorization Declined.

Amount: 100006 - 06 Error in Sequence Number. 

Amount: 100009 - 09 Wait Command.

Amount: 100098 - 98 Date and Time Not Plausible.

Example:

The amount of EUR 1000.02 ( <Amount>100002<Amount> ) will produce response code 02:
<Type>REJECTED</Type>
<Number>02</Number>
<Message>Call voice authorization number.</Message>

Any failure not specified by the Wirecard system will produce error code 250
<Type>REJECTED</Type> 
<Number>250</Number> 
<Message>System Error.</Message>

__lloyd@toyfoundry.com__

