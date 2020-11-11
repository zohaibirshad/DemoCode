        <div class="col-lg-4">
          <div class="user-profile-info-area">
            <ul class="links">
                @php 

                  if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
                  {
                    $link = "https"; 
                  }
                  else
                  {
                    $link = "http"; 
                      
                    // Here append the common URL characters. 
                    $link .= "://"; 
                      
                    // Append the host(domain name, ip) to the URL. 
                    $link .= $_SERVER['HTTP_HOST']; 
                      
                    // Append the requested resource location to the URL 
                    $link .= $_SERVER['REQUEST_URI']; 
                  }      

                @endphp
              <li class="{{ $link == route('user-dashboard',$storename) ? 'active':'' }}">
                <a href="{{ route('user-dashboard',$storename) }}">
                  {{ $langg->lang200 }}
                </a>
              </li>
              
              @if(Auth::user()->IsVendor())
                <li>
                  <a href="{{ route('vendor-dashboard',$storename) }}">
                    {{ $langg->lang230 }}
                  </a>
                </li>
              @endif

              <li class="{{ $link == route('user-orders',$storename) ? 'active':'' }}">
                <a href="{{ route('user-orders',$storename) }}">
                  {{ $langg->lang201 }}
                </a>
              </li>

              @if($gs->is_affilate == 1)

                <li class="{{ $link == route('user-affilate-code',$storename) ? 'active':'' }}">
                    <a href="{{ route('user-affilate-code',$storename) }}">{{ $langg->lang202 }}</a>
                </li>

                <li class="{{ $link == route('user-wwt-index',$storename) ? 'active':'' }}">
                    <a href="{{route('user-wwt-index',$storename)}}">{{ $langg->lang203 }}</a>
                </li>

              @endif


              <li class="{{ $link == route('user-order-track',$storename) ? 'active':'' }}">
                  <a href="{{route('user-order-track',$storename)}}">{{ $langg->lang772 }}</a>
              </li>

              <li class="{{ $link == route('user-favorites',$storename) ? 'active':'' }}">
                  <a href="{{route('user-favorites',$storename)}}">{{ $langg->lang231 }}</a>
              </li>

              <li class="{{ $link == route('user-messages',$storename) ? 'active':'' }}">
                  <a href="{{route('user-messages',$storename)}}">{{ $langg->lang232 }}</a>
              </li>

              <li class="{{ $link == route('user-message-index',$storename) ? 'active':'' }}">
                  <a href="{{route('user-message-index',$storename)}}">{{ $langg->lang204 }}</a>
              </li>

              <li class="{{ $link == route('user-dmessage-index',$storename) ? 'active':'' }}">
                  <a href="{{route('user-dmessage-index',$storename)}}">{{ $langg->lang250 }}</a>
              </li>

              <li class="{{ $link == route('user-profile',$storename) ? 'active':'' }}">
                <a href="{{ route('user-profile',$storename) }}">
                  {{ $langg->lang205 }}
                </a>
              </li>

              <li class="{{ $link == route('user-reset',$storename) ? 'active':'' }}">
                <a href="{{ route('user-reset',$storename) }}">
                 {{ $langg->lang206 }}
                </a>
              </li>

              <li>
                <a href="{{ route('user-logout',$storename) }}">
                  {{ $langg->lang207 }}
                </a>
              </li>

            </ul>
          </div>
          @if($gs->reg_vendor == 1)
            <div class="row mt-4">
              <div class="col-lg-12 text-center">
                <a href="{{ route('user-package',$storename) }}" class="mybtn1 lg">
                  <i class="fas fa-dollar-sign"></i> {{ Auth::user()->is_vendor == 1 ? $langg->lang233 : (Auth::user()->is_vendor == 0 ? $langg->lang233 : $langg->lang237) }}
                </a>
              </div>
            </div>
          @endif
        </div>