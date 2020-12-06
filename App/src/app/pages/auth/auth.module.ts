import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule } from "@angular/forms";

import { IonicModule } from "@ionic/angular";

import { AuthPageRoutingModule } from "./auth-routing.module";

import { AuthPage } from "./auth.page";
import { FontAwesomeModule } from "@fortawesome/angular-fontawesome";

@NgModule({
  imports: [CommonModule, FormsModule, IonicModule,FontAwesomeModule,AuthPageRoutingModule],
  declarations: [AuthPage],
})
export class AuthPageModule {}
