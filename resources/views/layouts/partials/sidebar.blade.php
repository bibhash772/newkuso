<nav class="pcoded-navbar menupos-fixed menu-light brand-blue ">
   <div class="navbar-wrapper ">
      <div class="navbar-brand header-logo">
         <a href="{{route('admin.admin-dashboard')}}" class="b-brand">CudosAPI </a>
         <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
      </div>
      <div class="navbar-content scroll-div   " >
         <ul class="nav pcoded-inner-navbar ">
            <li class="nav-item pcoded-menu-caption">
               <label>CudosApi</label>
            </li>
            <li data-username="dashboard default ecommerce sales Helpdesk ticket CRM analytics project" class="nav-item">
               <a href="/admin/home" class="nav-link"><span class="pcoded-micon"><i class="feather icon-home"></i></span>Dashboard</a>
            </li>
            
            @if(Auth::guard('admin')->user()->user_type==1)

            <li data-username="dashboard default ecommerce sales Helpdesk ticket CRM analytics project" class="nav-item pcoded-hasmenu">
               <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-heart"></i></span><span class="pcoded-mtext">Gift Cards</span></a>
               <ul class="pcoded-submenu">
                  <li class=""><a href="{{route('admin.gift-cards')}}" class="">Gift Cards</a></li>
               </ul>
            </li>
            @endif
         </ul>
      </div>
   </div>
</nav>