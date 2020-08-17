The passage of the data collected from the client (javascript) to drupal is necessary in order to be able to store them but also if necessary to format them or any necessary calculation in relation to the drupal or api data.

 

## Collection of data

Each tracking event listener button has a _data-tracking-context-submit_ attribute.

When the button is clicked, there is a collection of the values ​​of all the fields having the _data-tracking-context_ attribute and whose value of the latter contains the value of the button's tracking-context attribute.

The _data-tracking-field_ is used to name the label of the field at the collection level.

Exemples :

```
<form>   
  <input name=”nom” value=”Doe” data-tracking-context='report' data-tracking-field='name' />   
  <input name=”prenom” value=”John” data-tracking-context='info' data-tracking-field='first_name'  />   
  <input  name=”age” value=”26”  />   
  <input name=”profession” value=”Ingénieur” data-tracking-context='report' data-tracking-field='job'  />   
  <button data-tracking-context-submit='report'>OK</button>
</form>
```

When clicking on the OK button: we will have the collected data:

```
{   
  category : ‘report',￼
  name : ‘Doe’,￼
  job : 'Ingénieur’
}
```