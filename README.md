# InPlace Editing Plugin

Version 1.0.0

An in-place-edit plugin for [CakePHP](http://cakephp.org), which uses the power of jQuery. This plugin allows you to easily make any field in your views become editable.

Combined with the awesomeness of Ajax you can make changes to your data right from the view without even reloading the page.

## Requirements

* [jQuery](http://jquery.com/)
* [Jeditable](https://github.com/NicolasCARPi/jquery_jeditable)
* PHP version: PHP 7.1+
* CakePHP version: 3.5.X

## Installation

1. Go to your CakePHP App directory using a terminal.
2. type `composer require emilushi/in-place-editing`
3. To load the plugin type: `bin/cake plugin load InPlaceEditing`
4. Under `src/View/Application.php` on `initialize` method add the following line: `$this->loadHelper('InPlaceEditing.InPlaceEditing');`

#### Using InPlaceEditing on your view

The editing helper will allow you to add an `input` control to your views that will behave like the a div element (by default, or any other HTML element if you wish) on your view until you click/double-click/hover/etc on it, then it will appear as a text input, or a drop-down list, or any element supported by [Jeditable](https://github.com/NicolasCARPi/jquery_jeditable) jQuery plugin.
Let's assume our Model is **OrdersTable**, Controller **Orders** and field to edit is called **comments**. Add the code below under your view to generate the needed to field which will trigger `jeditable`.
  
    <?= $this->InPlaceEditing->input('Order', 'comments', $order->id, [
        'value'         => $order->comments,
        'actionName'    => $this->Url->build([
            'controller' => 'orders',
            'action'     => 'in_place_editing'
        ]),
        'type'          => 'textarea',
        'rows'          => 4,
        'cancelText'    => 'Cancel',
        'submitText'    => 'Save',
        'toolTip'       => 'Click to edit',
        'containerType' => 'div'
    ]) ?>

#### Add an action handler in your controller

Before you may need to disable security component for this specific action by adding: `$this->Security->setConfig('unlockedActions', ['inPlaceEditing']);` on `beforeFilter` method of your controller.
Then add the following action on your controller which will update our **$order->comments** field and return the new value to the view.
    
    public function inPlaceEditing($id = null) {
        $this->getRequest()->allowMethod('ajax');
        
        $order = $this-Orders->get($id);
        
        //You may need to unset data['id']
        if(isset($data['id'])) {
            unset($data['id']);
        }      
        
        $order = $this->Orders->pathchEntity($order, $data);
        
        if($this->Orders->save($order)) {
            $comment = $order->comments;
        
            $this->set(compact('comment'));
            $this->set('_serialize', 'comment');
            
            $this->viewBuilder()->disableAutoLayout();
            $this->viewBuilder()->setClassName('Ajax');
        }
    }

#### Create the action handler view

Since we set the view class name to **Ajax** on our action we need to create a new ajax view on the above path:

    src/Template/Orders/ajax/in_place_editing.ctp
    
**Note, that you need to replace Orders with your own controller Name.** and on the new file you created add the above code:

    echo $comment;
    
**Note, here as well you need to replace `$comment` with you own field.**

#### Loading JS

InPlaceEditingHelper will generate an input for your field and a piece of jQuery code for each field.
The generated jQuery code is injected using `$this->Html->scriptBlock()` method which requires that you have somewhere on your layout a `fetch` method and it needs to be below the code you use for loading `jQuery` and `jquery-jeditable`.
Which will look like: `<?= $this->fetch('script') ?>`


## ToDo

This version of the plugin is by no means perfect, but it works. I'll be working on making things a lot easier in the future updates.
* Write tests.

## License

This code is licensed under the [MIT license](http://www.opensource.org/licenses/mit-license.php).

## Notes

Feel free to submit bug reports or suggest improvements in a ticket or fork this project and improve upon it yourself. Contributions welcome.