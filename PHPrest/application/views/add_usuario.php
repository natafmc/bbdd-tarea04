<?php $this->load->view('commons/header'); ?>
<body>
    <div class="container">
        <div class="row m-5">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header alert-primary">
                        <h3>SUSCRIBIR USUARIO</h3>
                    </div>
                    <div class="card-body">                        
                        
                        
                        <form class="text-center border border-light p-5" action="<?php echo base_url()?>Usuario/add" method="POST">
                            <input type="hidden" name="id" class="form-control mb-2" value="<?php if($usuario != null) echo $usuario->id;?>">
                            <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre(s)"  value="<?php if($usuario != null)  echo $usuario->nombre;?>">
                            <input type="text" name="apellido" class="form-control mb-2" placeholder="Apellido(s)" value="<?php  if($usuario != null) echo $usuario->apellido;?>">
                            <input type="text" name="email" class="form-control mb-2" placeholder="E-mail"  value="<?php if($usuario != null)  echo $usuario->email;?>">
                            <input type="text" name="nickname" class="form-control mb-2" placeholder="Nombre de Usuario"  value="<?php  if($usuario != null) echo $usuario->username;?>">
                            <input type="password" name="password" class="form-control mb-2" placeholder="Password" value="<?php  if($usuario != null) echo $usuario->password;?>">
                            <input type="text" name="telefono" class="form-control mb-2" placeholder="Tel&eacute;fono" value="<?php  if($usuario != null) echo $usuario->telefono;?>"> 
                            <button class="btn btn-info btn-block" type="submit">CREAR USUARIO</button>
                        </form>


                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header alert-primary">
                        <h3>Lista de usuarios</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list">
                            <?php foreach($usuarioList as $usuario):?>
                                <li class="list-group-item"><?php echo $usuario->nickname;?> <a href="" class="btn btn-info float-right"> <span aria-hidden="true">&times;</span> </a> </li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>