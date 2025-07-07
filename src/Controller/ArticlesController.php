<?php
// src/Controller/ArticlesController.php

namespace App\Controller;

class ArticlesController extends AppController
{
    public function index()
    {
        echo "Test";
        $this->loadComponent('Paginator');
        $articles = $this->Paginator->paginate($this->Articles->find());
        $this->set(compact('articles'));
    }

    public function view($slug = null)
    {
        $article = $this->Articles
            ->findBySlug($slug)
            ->contain('Tags')
            ->firstOrFail();
        
        $this->set(compact('article'));
    }

    public function add()
    {
        // 1. Crear una nueva entidad
        $article = $this->Articles->newEmptyEntity();
        
        // 2. Si el método es POST: 
        if($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            
            $article->user_id = 1;

            // 3. Guardar artículo
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your articles has been saved'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your article'));
        }

        // Get list of tags
        $tags = $this->Articles->Tags->find('list')->all();

        // Set tags to the view context
        $this->set('tags', $tags);
        $this->set('article', $article);
    }

    public function edit($slug) 
    {   
        // 1. Obtener el articulo mediante slug
        $article = $this->Articles
            ->findBySlug($slug)
            ->contain('Tags')
            ->firstOrFail();

        // 2. Evaluar si la request es put o post
        if ($this->request->is(['put', 'post'])) {
            // 3. Actualizar el articulo mediante su entidad
            $this->Articles->patchEntity($article, $this->request->getData());

            // 4. Guardar el articulo mediante su modelo
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been updated'));

                // 5. Redireccionar a la vista de articulos
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Unable to update your article.'));
        }

        // 6. get a list of tags
        $tags = $this->Articles->Tags->find('list')->all();
        
        // 7. Pasar por defecto la variable article con su contenido a la vista
        $this->set('tags', $tags);
        $this->set('article', $article);
    }

    public function delete($slug)
    {
        // 1. Evaluar método HTTP permitidos
        $this->request->allowMethod(['post', 'delete']);

        // 2. Buscar el artículo por slug
        $article = $this->Articles
            ->findBySlug($slug)
            ->firstOrFail();

        // 3. Eliminar el artíclo
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The {0} article has been deleted.', $article->title));

            return $this->redirect(['action' => 'index']);
        }    

        $this->Flash->error(_('Unable to delete you article'));
        $this->redirect(['action' => 'index']);
    }

    public function tags()
    {
        /*
            The 'pass' key is provided by CakePHP and contains all
            the passed URL path segments in the request.
            
            Como los argumentos están siendo pasados como parámetros del método, podríamos usar PHP Variadic:
            
            public function tags(...tags){}
        */
        
        $tags = $this->request->getParam('pass');

        // 1. Obtener articulos etiquetados
        $articles = $this->Articles->find('tagged', [
            'tags' => $tags
        ])->all();
        
        // 2. Devolverlos en una vista
        $this->set([
            'articles' => $articles,
            'tags' => $tags
        ]);
    }
}


