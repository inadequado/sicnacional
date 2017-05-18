<?php

class TipoNutricaoForm extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();

       

        $this->form = new BootstrapFormBuilder( "form_cadastro_tiponutricao" );
        $this->form->setFormTitle( "Cadastro Tipo Nutri&ccedil;&atilde;o" );
        $this->form->class = "tform";

        // create the form fields
        $id = new THidden('id');
        $nome = new TEntry('nome');

        // add the fields
        $this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel('Tipo de Nutri&ccedil;&atilde;o')], [$nome] );


        $id->setEditable(FALSE);
        $id->setSize('38%');
        $nome->setSize('70%');
        $nome->addValidation('Tipo de Nutri&ccedil;&atilde;o', new TRequiredValidator );

        // create the form actions
       $this->form->addAction( "Salvar", new TAction( [ $this, "onSave" ] ), "fa:floppy-o" );
        $this->form->addAction( "Voltar para listagem", new TAction( [ "TipoNutricaoList", "onReload" ] ), "fa:table blue" );
       
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        $container->add(new TXMLBreadCrumb('menu.xml', 'TipoNutricaoList'));
        $container->add($this->form);

        parent::add($container);
    }

    public function onSave()
    {
        try
        {
            //Validacao do formulario
            $this->form->validate();

            TTransaction::open( "dbsic" );

            //Resgata os dados inseridos no formulario a partir do modelo
            $object = $this->form->getData( "TipoNutricaoRecord" );

            //Resgata o usuario a data e hora da alteracao do registro
            //$object->usuarioalteracao = TSession::getValue("login");
            //$object->dataalteracao = date( "Y-m-d H:i:s" );

            $object->store();

            TTransaction::close();

            $action = new TAction( [ "TipoNutricaoList", "onReload" ] );

            new TMessage( "info", "Registro salvo com sucesso!", $action );

            // TApplication::gotoPage("CadastroClientesList", "onReload");
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();

            new TMessage( "error", "Ocorreu um erro ao tentar salvar o registro!<br><br>" . $ex->getMessage() );
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if( isset( $param[ "key" ] ) )
            {
                TTransaction::open( "dbsic" );

                $object = new TipoNutricaoRecord( $param[ "key" ] );

                $this->form->setData( $object );

                TTransaction::close();
            }
        }
        catch ( Exception $ex )
        {
            TTransaction::rollback();

            new TMessage( "error", "Ocorreu um erro ao tentar carregar o registro para edição!<br><br>" . $ex->getMessage() );
        }
    }
}