<li>

  <a href="#order" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false"><i class="fas fa-hand-holding-usd"></i>{{ __('Orders') }}</a>

  <ul class="collapse list-unstyled" id="order" data-parent="#accordion" >

   <li>

    <a href="{{route('admin-order-index',$storename)}}"> {{ __('All Orders') }}</a>

  </li>

  <li>

    <a href="{{route('admin-order-pending',$storename)}}"> {{ __('Pending Orders') }}</a>

  </li>

  <li>

    <a href="{{route('admin-order-processing',$storename)}}"> {{ __('Processing Orders') }}</a>

  </li>

  <li>

    <a href="{{route('admin-order-completed',$storename)}}"> {{ __('Completed Orders') }}</a>

  </li>

  <li>

    <a href="{{route('admin-order-declined',$storename)}}"> {{ __('Declined Orders') }}</a>

  </li>

  <li>

    <a href="{{url($storename.'/admin/analytics')}}"> {{ __('Orders Analytics') }}</a>

  </li> 

</ul>

</li>

<li>

  <a href="#menu2" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">

    <i class="icofont-cart"></i>{{ __('Products') }}

  </a>

  <ul class="collapse list-unstyled" id="menu2" data-parent="#accordion">

    <li>

      <a href="{{ route('admin-prod-types',$storename) }}"><span>{{ __('Add New Product') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-prod-import',$storename) }}">{{ __('Bulk Product Upload') }}</a>
    
    </li>

    <li>

      <a href="{{ route('admin-prod-index',$storename) }}"><span>{{ __('All Products') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-prod-popular',[$storename,30]) }}"><span>{{ __('Popular Products') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-prod-deactive',$storename) }}"><span>{{ __('Deactivated Product') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-prod-catalog-index',$storename) }}"><span>{{ __('Product Catalogs') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-coupon-index',$storename) }}" class=" wave-effect">{{ __('Set Coupons') }}</a>
    
    </li>

    <li>
      <a href="{{ route('products-ali-express-import',$storename) }}" class=" wave-effect">{{ __('Import Ali Express Product') }}</a>
    </li>
  </ul>

</li>



<li>

  <a href="#affiliateprod" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">

    <i class="icofont-cart"></i>{{ __('Affiliate Products') }}

  </a>

  <ul class="collapse list-unstyled" id="affiliateprod" data-parent="#accordion">

    <li>

      <a href="{{ route('admin-import-create',$storename) }}"><span>{{ __('Add Affiliate Product') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-import-index',$storename) }}"><span>{{ __('All Affiliate Products') }}</span></a>

    </li>

  </ul>


</li>


<li>

  <a href="#menu3" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">

    <i class="icofont-user"></i>{{ __('Customers') }}

  </a>

  <ul class="collapse list-unstyled" id="menu3" data-parent="#accordion">

    <li>

      <a href="{{ route('admin-user-index',$storename) }}"><span>{{ __('Customers List') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-withdraw-index',$storename) }}"><span>{{ __('Withdraws') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-user-image',$storename) }}"><span>{{ __('Customer Default Image') }}</span></a>

    </li>

  </ul>

</li>



<li>

  <a href="#vendor" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">

    <i class="icofont-ui-user-group"></i>{{ __('Vendors') }}

  </a>

  <ul class="collapse list-unstyled" id="vendor" data-parent="#accordion">

    <li>

      <a href="{{ route('admin-vendor-index',$storename) }}"><span>{{ __('Vendors List') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-vendor-withdraw-index',$storename) }}"><span>{{ __('Withdraws') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-vendor-subs',$storename) }}"><span>{{ __('Vendor Subscriptions') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-vendor-color',$storename) }}"><span>{{ __('Default Background') }}</span></a>

    </li>



    <li>

      <a href="{{ route('admin-vr-index',$storename) }}"><span>{{ __('All Verifications') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-vr-pending',$storename) }}"><span>{{ __('Pending Verifications') }}</span></a>

    </li>



    <li>

      <a href="{{ route('admin-subscription-index',$storename) }}" ><span>{{ __('Vendor Subscription Plans') }}</span></a>

    </li>



  </ul>

</li>













<li>

  <a href="#menu5" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false"><i class="fas fa-sitemap"></i>{{ __('Manage Categories') }}</a>

  <ul class="collapse list-unstyled

  @if(request()->is('admin/attribute/*/manage') && request()->input('type')=='category')

  show

  @elseif(request()->is('admin/attribute/*/manage') && request()->input('type')=='subcategory')

  show

  @elseif(request()->is('admin/attribute/*/manage') && request()->input('type')=='childcategory')

  show

  @endif" id="menu5" data-parent="#accordion" >

  <li class="@if(request()->is('admin/attribute/*/manage') && request()->input('type')=='category') active @endif">

    <a href="{{ route('admin-cat-index',$storename) }}"><span>{{ __('Main Category') }}</span></a>

  </li>

  <li class="@if(request()->is('admin/attribute/*/manage') && request()->input('type')=='subcategory') active @endif">

    <a href="{{ route('admin-subcat-index',$storename) }}"><span>{{ __('Sub Category') }}</span></a>

  </li>

  <li class="@if(request()->is('admin/attribute/*/manage') && request()->input('type')=='childcategory') active @endif">

    <a href="{{ route('admin-childcat-index',$storename) }}"><span>{{ __('Child Category') }}</span></a>

  </li>

</ul>

</li>







<li>

  <a href="#menu4" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">

    <i class="icofont-speech-comments"></i>{{ __('Product Discussion') }}

  </a>

  <ul class="collapse list-unstyled" id="menu4" data-parent="#accordion">

    <li>

      <a href="{{ route('admin-rating-index',$storename) }}"><span>{{ __('Product Reviews') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-comment-index',$storename) }}"><span>{{ __('Comments') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-report-index',$storename) }}"><span>{{ __('Reports') }}</span></a>

    </li>

  </ul>

</li>





<li>

  <a href="#blog" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">

    <i class="fas fa-fw fa-newspaper"></i>{{ __('Blog') }}

  </a>

  <ul class="collapse list-unstyled" id="blog" data-parent="#accordion">

    <li>

      <a href="{{ route('admin-cblog-index',$storename) }}"><span>{{ __('Categories') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-blog-index',$storename) }}"><span>{{ __('Posts') }}</span></a>

    </li>

  </ul>

</li>



<li>

  <a href="#msg" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">

    <i class="fas fa-fw fa-newspaper"></i>{{ __('Messages') }}

  </a>

  <ul class="collapse list-unstyled" id="msg" data-parent="#accordion">

    <li>

      <a href="{{ route('admin-message-index',$storename) }}"><span>{{ __('Tickets') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-message-dispute',$storename) }}"><span>{{ __('Disputes') }}</span></a>

    </li>

  </ul>

</li>







<li>

  <a href="#seoTools" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">

    <i class="fas fa-wrench"></i>{{ __('Analytics') }}
  </a>

  <ul class="collapse list-unstyled" id="seoTools" data-parent="#accordion">

    

    <li>

      <a href="{{ route('admin-seotool-analytics',$storename) }}"><span>{{ __('Google Analytics') }}</span></a>

      </li>

      <li>

        <a href="{{ route('admin-seotool-pixel',$storename) }}"><span>{{ __('Facebook Pixel') }}</span></a>
  
        </li>
      <li>

        <a href="{{ route('admin-seotool-keywords',$storename) }}"><span>{{ __('Website Meta Keywords') }}</span></a>

      </li>

    </ul>

  </li>


  <li>

    <!-- <a href="{{ route('admin-cache-clear',$storename) }}" class=" wave-effect"><i class="fas fa-sync"></i>{{ __('Clear Cache') }}</a> -->

  </li>
{{-- new --}}
  <li>

    <a href="#manage" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">

      <i class="fas fa-cog"></i>{{ __('Manage') }}

    </a>

    <ul class="collapse list-unstyled" id="manage" data-parent="#accordion">



      <li><a href="{{ route('admin-role-index',$storename) }}" class=" wave-effect">{{ __('Manage Roles') }}</a>
      </li>

      <li><a href="{{ route('admin-subs-index',$storename) }}" class=" wave-effect">{{ __('Subscribers') }}</a>

      </li>

      <li>

        <a href="{{ route('admin-staff-index',$storename) }}" class=" wave-effect">{{ __('Manage Staffs') }}</a>
    
      </li>

    </ul>

  </li>
{{-- new --}}

  <li>

    <a href="#sactive" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">

      <i class="fas fa-cog"></i>{{ __('System Activation') }}

    </a>

    <ul class="collapse list-unstyled" id="sactive" data-parent="#accordion">



      <li><a href="{{route('admin-activation-form',$storename)}}"> {{ __('Activation') }}</a></li>

       

    </ul>

  </li>



  <li>

  <a href="#general" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">

    <i class="fas fa-cogs"></i>{{ __('General Settings') }}

  </a>

  <ul class="collapse list-unstyled" id="general" data-parent="#accordion">

    <li>

      <a href="{{ route('admin-gs-logo',$storename) }}"><span>{{ __('Logo') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-gs-fav',$storename) }}"><span>{{ __('Favicon') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-gs-load',$storename) }}"><span>{{ __('Loader') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-shipping-index',$storename) }}"><span>{{ __('Shipping Methods') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-package-index',$storename) }}"><span>{{ __('Packagings') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-pick-index',$storename) }}"><span>{{ __('Pickup Locations') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-gs-contents',$storename) }}"><span>{{ __('Website Contents') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-gs-footer',$storename) }}"><span>{{ __('Footer') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-gs-affilate',$storename) }}"><span>{{__('Affiliate Information')}}</span></a>

    </li>



    <li>

      <a href="{{ route('admin-gs-popup',$storename) }}"><span>{{ __('Popup Banner') }}</span></a>

    </li>





    <li>

      <a href="{{ route('admin-gs-error-banner',$storename) }}"><span>{{ __('Error Banner') }}</span></a>

    </li>



    <li>

      <a href="{{ route('admin-gs-maintenance',$storename) }}"><span>{{ __('Website Maintenance') }}</span></a>

    </li>



    <li>

      <a href="{{ route('admin-sl-index',$storename) }}"><span>{{ __('Sliders') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-service-index',$storename) }}"><span>{{ __('Services') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-ps-best-seller',$storename) }}"><span>{{ __('Right Side Banner1') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-ps-big-save',$storename) }}"><span>{{ __('Right Side Banner2') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-sb-index',$storename) }}"><span>{{ __('Top Small Banners') }}</span></a>

    </li>



    <li>

      <a href="{{ route('admin-sb-large',$storename) }}"><span>{{ __('Large Banners') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-sb-bottom',$storename) }}"><span>{{ __('Bottom Small Banners') }}</span></a>

    </li>



    <li>

      <a href="{{ route('admin-review-index',$storename) }}"><span>{{ __('Reviews') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-partner-index',$storename) }}"><span>{{ __('Partners') }}</span></a>

    </li>





    <li>

      <a href="{{ route('admin-ps-customize',$storename) }}"><span>{{ __('Home Page Customization') }}</span></a>

    </li>



    <li>

      <a href="{{ route('admin-faq-index',$storename) }}"><span>{{ __('FAQ Page') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-ps-contact',$storename) }}"><span>{{ __('Contact Us Page') }}</span></a>

    </li>

    <li>

      <a href="{{ route('admin-page-index',$storename) }}"><span>{{ __('Other Pages') }}</span></a>

    </li>



    <li><a href="{{route('admin-mail-index',$storename)}}"><span>{{ __('Email Template') }}</span></a></li>

    <li><a href="{{route('admin-mail-config',$storename)}}"><span>{{ __('Email Configurations') }}</span></a></li>

    <li><a href="{{route('admin-group-show',$storename)}}"><span>{{ __('Group Email') }}</span></a></li>



    <li><a href="{{route('admin-gs-payments',$storename)}}"><span>{{__('Payment Information')}}</span></a></li>

    <li><a href="{{route('admin-payment-index',$storename)}}"><span>{{ __('Payment Gateways') }}</span></a></li>

    <li><a href="{{route('admin-currency-index',$storename)}}"><span>{{ __('Currencies') }}</span></a></li>



    <!-- <li><a href="{{route('admin-social-index',$storename)}}"><span>{{ __('Social Links') }}</span></a></li> -->

    <!-- <li><a href="{{route('admin-social-facebook',$storename)}}"><span>{{ __('Facebook Login') }}</span></a></li> -->

    <!-- <li><a href="{{route('admin-social-google',$storename)}}"><span>{{ __('Google Login') }}</span></a></li> -->



    <li><a href="{{route('admin-lang-index',$storename)}}"><span>{{ __('Website Language') }}</span></a></li>

    <li><a href="{{route('admin-tlang-index',$storename)}}"><span>{{ __('Admin Panel Language') }}</span></a></li>



  </ul>

</li>