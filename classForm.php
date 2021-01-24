<?php
/*
    class Form{

    private $data;
    private $method;
    public $surround = "p";
    private $contenu= 'xxxxx';

    public function __construct( $method='get'){

        //$this->data = $data;
        $this->method= $method;

    }

    private function surround($html)
    {

        return "<{$this->surround}>{$html}</{$this->surround}>";
    }

    private function getValue($index){

        return   isset($this->data[$index]) ? $this->data[$index] : null;
    }

    public function input($name){
        $this->contenu.= $this->surround(
            '<input type= "text" name="' . $name.  '"value ="' .$this->getValue($name).'">'
        );

        }

    public function label(string $for, string $texte)
        {
            // On ouvre la balise
            $this->contenu .= '<label for='.$for. '>'.$texte.'</label>';

        }

    public function ajoutTextarea($nom, $row,$col, $id){

        return '<textarea name="'.$nom .'"rows="'.$row.'"cols="'.$col.'"id="'.$id. '" ></textarea>' ;  }

    public function listeGeneral($texteOption,  $nom,  $id){
        //return ' <select name="'.$nom.'" id="'.$id.'"><option value="">'.$texteOption.'</option>'.$options_categorie.'</select>';


}
    public function  monFormulaire(){
        return '<Form action="" method="'.$this->method.'">'.$this->contenu.  '</form>';
    }
}

$form = new Form('post');
    $form->label('nomProduit', 'Le nom du produit');
$form->label('nomProduit', 'Le nom du produit');
$form->label('nomProduit', 'Le nom du produit');
$form->input('nom');
    echo $form->monFormulaire();
?>



<?php
echo $form->ajoutTextarea('descProduit', 20,20,'nProduit');

*/


class Form
{


    private $method;

    private $contenu = '';


    public function __construct($method = 'get')
    {


        $this->method = $method;

    }
    private function surround($html)
    {

        return "<{$this->surround}>{$html}</{$this->surround}>";
    }

    private function getValue($index){

        return   isset($this->data[$index]) ? $this->data[$index] : null;
    }

    public function input($name){
        $this->contenu.= $this->surround(
            '<input type= "text" name="' . $name.  '"value ="' .$this->getValue($name).'">'
        );

    }


    public function select($texteOption, $nom, $id)
    {

        $this->continu .= '<select name="' . $nom . '" id="' . $id . '>' . $texteOption . '</select>';
    }


    public function monFormulaire()
    {
        return '<Form action="" method="' . $this->method . '">' . $this->contenu . '</form>';

    }
}


?>



