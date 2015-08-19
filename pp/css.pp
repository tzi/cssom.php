%skip   space          \s

// Properties
%token  colon          :
%token  semicolon      ;
%token  comma          ,

// Hierarchy
%token  brace_         {
%token _brace          }

// Core
%token selectorOperator \s|\>|\+|\~
%token string          [a-z]+
%token dashed_string   [a-z\-]+
%token value           [0-9]+

#rule:
    selectorList() ::brace_:: declarationList()? ::_brace::

selectorList:
    selector() ( ::comma:: selector() )* #selectorList

selector:
    selectorNode() ( ::selectorOperator:: selectorNode() )*

selectorNode:
    <dashed_string>|<string>

declarationList:
    declaration() ( ::semicolon:: declaration() )* ::semicolon::? #declarationList

declaration:
    properties() ::colon:: <value>

properties:
    <dashed_string>|<string>


