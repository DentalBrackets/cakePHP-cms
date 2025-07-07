<!-- in /templates/Users/login.php -->
 <div class="users form">
    <?= $this->Flash->render() ?>
    <h3>Login</h3>
    <?= $this->Form->create() ?> 
    <fieldset>
        <legend><?php __('Please enter your username and password') ?></legend>
        <?= $this->Form->control('email', ['required' => true]) ?>
        <?= $this->Form->control('password', ['required' => true]) ?>
    </fieldset>
    <?= $this->Form->submit(__('Login')); ?>
    <?= $this->Form->end() ?> 

    <span>Don't you have a user yet?</span> <?= $this->Html->link('Sign up', ['action' => 'add']) ?>
 </div>