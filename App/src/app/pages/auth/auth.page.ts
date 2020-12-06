import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-auth',
  templateUrl: './auth.page.html',
  styleUrls: ['./auth.page.scss'],
})
export class AuthPage implements OnInit {

  constructor() { }

  ngOnInit() {
  }


  signupbtn(){
    const container = document.querySelector("#container");
    container.classList.add("sign-up-mode");
  }

  signinbtn(){
    const container = document.querySelector("#container");
	  container.classList.remove("sign-up-mode");
  }

}
