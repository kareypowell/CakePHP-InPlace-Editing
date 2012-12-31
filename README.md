# InPlace Editing Plugin

Version 0.0.1

An in-place-edit plugin for [CakePHP](http://cakephp.org), which uses the power of jQuery. This plugin allows you to easily make any field in your views become editable.

Combined with the awesomeness of Ajax you can make changes to your data right from the view without even reloading the page.

## Requirements

* [jQuery](http://jquery.com/)
* [Jeditable](http://www.appelsiini.net/projects/jeditable)
* PHP version: PHP 5.3+
* CakePHP version: 2.2.x

## Installation

Go to your CakePHP `app/Plugin` directory.

#### As an Archive  

* `git clone https://github.com/kareypowell/CakePHP-InPlace-Editing.git InPlaceEditing`

#### As a Submodule

* `$ git submodule add https://github.com/kareypowell/CakePHP-InPlace-Editing.git InPlaceEditing`

## Usage

Applying the `InPlaceEditing` helper to your controller is essentially the same as applying any other CakePHP helpers.

* In your `Config/bootstrap.php` load plugin with `CakePlugin::load('InPlaceEditing');`
* Include InPlaceEditing helper in your controller with:
  * `public $helpers = array('InPlaceEditing.InPlaceEditing');`

### Next Steps

#### Add an in-place-editing control to your view

The editing helper will allow you to add an `input` control to your views that will behave like the a div element (by default, or any other HTML element if you wish) on your view until you click/double-click/hover/etc on it, then it will appear as a text input, or a drop-down list, or any element supported by [Jeditable](http://www.appelsiini.net/projects/jeditable) jQuery plugin.
  
    echo $this->inPlaceEditing->input('User', 'first_name', $user['User']['id'],
            array('value' => $user['User']['first_name'],
                  'actionName' => 'in_place_editing',
                  'type' => 'text',
                  'cancelText' => 'Cancel',
                  'submitText' => 'Save',
                  'toolTip' => 'Click to edit First Name',
                  'containerType' => 'dd'
                  )
            );

#### Add an action handler in your controller

When the save button is pressed after modifying the in-place-edit element, a post is made to the inPlaceEditing (by default) controller action. You can add a function like this to handle the in-place-editing action.

    public function in_place_editing($id = null) {
      
      if (!$id) return;

      if ($this->request->data) {
        # get all the fields with its values (there should be only one, but anyway ...)
        foreach($this->data['User'] as $field => $value)
        {
          # check if the provided field name is acceptable
          switch($field)
          {
            case 'zip':
            case 'first_name':
              break;
            default:
              $this->set('updated_value', '');
            return;
          }
           
          $this->User->id = $id;
          $this->User->saveField($field, $value);
           
          $this->set('updated_value', $value);
          $this->beforeRender(); 
          $this->layout = 'ajax';
        }
      }
      
    }

#### Create the action handler view

Since you have a new action in your controller which is used for the in-place-editing functionality, you will need to add the in_place_editing view to your model’s views folder. And that view should display the updated result after save.

  *     <?php echo $updated_value; ?>

#### Enabling Ajax to prevent unwanted things from showing after the update.

* The first thing that should done is to enable the `Session` and `RequestHandler` components in your `AppController` or any controller of your choosing that you want to take advantage of this plugin with:

  `public $components = array('Session', 'RequestHandler');`

* Next, we need to add some code to the `AppController::beforeRender()` action to check if the request coming through is an Ajax call and disable the debug as necessary. Here is the code:

        if($this->RequestHandler->isAjax() || $this->RequestHandler->isXml()) {  
          Configure::write('debug', 0);
        }


## ToDo

* Write tests.

## License

This code is licensed under the [MIT license](http://www.opensource.org/licenses/mit-license.php).

## Notes

Feel free to submit bug reports or suggest improvements in a ticket or fork this project and improve upon it yourself. Contributions welcome.