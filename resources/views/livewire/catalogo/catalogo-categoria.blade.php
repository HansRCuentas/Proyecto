<div>
    <!-- NAVEGACION -->
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{route('catalogo')}}">
                    <img src="{{url('/images/Vlashey.jpeg')}}" style="height: 50px;" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link  h5" aria-current="page" href="{{route('catalogo')}}"><b>INICIO</b></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  h5" aria-current="page" href="{{route('nosotros')}}"><b>CONTACTO</b></a>
                        </li>
                        <li class="nav-item dropdown ml-5 mr-8 h5">
                            <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <b>CATEGORIAS</b>
                            </a>
                            <ul class="dropdown-menu bg-danger" aria-labelledby="navbarDropdown">
                                @foreach ($categorias as $cat)
                                @php
                                $sw=false;
                                @endphp
                                @foreach ($cat->productos as $pro)
                                @if($pro->indicador==1)
                                @php
                                $sw=true;
                                @endphp
                                @endif
                                @endforeach
                                @if($sw)
                                <li><a class="dropdown-item h5 {{$cat->id == $catId ? 'active' : ''}}" type="button" wire:click="$set('catId',{{$cat->id}})">{{$cat->nombre}}</a></li>
                                @endif
                                @endforeach
                            </ul>
                        </li>

                    </ul>
                    <form class="d-flex pl-6 w-100">
                        <input class="form-control" type="search" placeholder="Buscar producto" aria-label="Search" wire:model="buscar">
                    </form>

                </div>
            </div>
        </nav>
    </div>
    <!-- FIN DE NAVEGACION -->
    <!-- PARA MOSTRAR LOS PRODUCTOS -->
    <div class="container-fluid my-5 pt-4 container">
        <div class="card bg-dark text-center text-white mt-3">
            <h4 class=" fw-bold mt-2 mb-2">{{$categoria->nombre}}</h4>
        </div>
        <div class="row mt-3">
            @if(count($productos)>0)
            @foreach ($productos as $prod)
            @if($prod->indicador==1)
            <div class="item col-md-3 mb-5">
                <div class="card border-4 shadow">
                    <img src="{{asset('storage/'.$prod->imagen)}}" alt="" class="card-img-top">
                    <div class="card-body">
                        <div class="card-title text-center">
                            <h4>{{$prod->nombre}}</h4>
                            <h5 class="card-text text-muted">Precio: <span class="badge bg-dark">Bs. {{$prod->precio_venta}}</span></h5>
                            <p class="card-text fst-italic">Some quick example text to build on the card title and make up the bulk of the card's content.</p>

                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
            @else
            <div class="card">
                <div class="alert alert-dark col-md-12 mt-2 mb-2" role="alert">
                    No se encontraron registros
                </div>
            </div>

            @endif
        </div>
    </div>
</div>