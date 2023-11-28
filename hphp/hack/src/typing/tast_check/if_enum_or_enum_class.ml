(*
 * Copyright (c) Meta Platforms, Inc. and affiliates.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the "hack" directory of this source tree.
 *
 *)
open Hh_prelude

type kind =
  | Enum of {
      name: string;
      class_decl: Decl_provider.class_decl;
    }
  | EnumClass of {
      name: string;
      interface: Typing_defs.locl_ty;
      class_decl: Decl_provider.class_decl;
    }
  | EnumClassLabel of {
      name: string;
      interface: Typing_defs.locl_ty;
      class_decl: Decl_provider.class_decl;
    }

let name = function
  | Enum { name; class_decl = _ }
  | EnumClass { name; interface = _; class_decl = _ }
  | EnumClassLabel { name; interface = _; class_decl = _ } ->
    name

let decl = function
  | Enum { name = _; class_decl }
  | EnumClass { name = _; interface = _; class_decl }
  | EnumClassLabel { name = _; interface = _; class_decl } ->
    class_decl

(* Small reminder:
 * - enums are localized to `Tnewtype (name, _, _)` where name is the name of
 *   the enum. This can be checked using `Env.is_enum`
 * - enum classes are not inhabited by design. The type of elements is
 *   HH\MemberOf<name, interface> were name is the name of the enum class. This
 *   is localized as Tnewtype("HH\MemberOf", [enum; interface]) where
 *   enum is localized as Tclass(name, _, _) where Env.is_enum_class name is
 *   true.
 *)
(* Wrapper to share the logic that detects if a type is an enum or an enum
 * class, or something else.
 *)
let apply env ~(default : 'a) ~(f : kind -> 'a) name args =
  let check_ec kind = function
    | [enum; interface] -> begin
      match Typing_defs.get_node enum with
      | Typing_defs.Tclass ((_, cid), _, _) when Tast_env.is_enum_class env cid
        ->
        Option.value_map
          ~default
          (Tast_env.get_enum env cid |> Decl_entry.to_option)
          ~f:(fun class_decl ->
            match kind with
            | `EnumClass -> f (EnumClass { name = cid; interface; class_decl })
            | `EnumClassLabel ->
              f (EnumClassLabel { name = cid; interface; class_decl }))
      | _ -> default
    end
    | _ -> default
  in
  match Tast_env.get_enum env name with
  | Decl_entry.Found class_decl -> f (Enum { name; class_decl })
  | Decl_entry.DoesNotExist ->
    if String.equal name Naming_special_names.Classes.cMemberOf then
      check_ec `EnumClass args
    else if String.equal name Naming_special_names.Classes.cEnumClassLabel then
      check_ec `EnumClassLabel args
    else
      default
  | Decl_entry.NotYetAvailable -> default
