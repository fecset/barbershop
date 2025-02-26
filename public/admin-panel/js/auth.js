// document.addEventListener("DOMContentLoaded", () => {
//     const authForm = document.getElementById("authForm");
//
//     if (authForm) {
//         authForm.addEventListener("submit", async (event) => {
//             event.preventDefault();
//
//             const login = document.getElementById("username").value.trim();
//             const password = document.getElementById("password").value;
//             const authError = document.getElementById("authError");
//
//             authError.style.display = "none";
//
//             try {
//                 const response = await fetch("/admin-panel/auth", {
//                     method: "POST",
//                     headers: {
//                         "Content-Type": "application/json",
//                         "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
//                     },
//                     body: JSON.stringify({
//                         login: login,
//                         password: password,
//                     }),
//                 });
//
//                 const result = await response.json();
//
//                 if (response.ok && result.status === "success") {
//                     localStorage.setItem("isLoggedIn", "true");
//                     localStorage.setItem("role", result.role);
//                     localStorage.setItem("currentUser", JSON.stringify(result.user));
//                     window.location.href = "/admin-panel/dashboard";
//                 } else {
//                     authError.textContent = result.message || "Неверный логин или пароль";
//                     authError.style.display = "block";
//                 }
//             } catch (error) {
//                 authError.textContent = "Ошибка при авторизации";
//                 authError.style.display = "block";
//             }
//         });
//     }
// });
//
//
// /**
//  * Функция для загрузки администраторов
//  */
// async function loadAdmins() {
//     try {
//         const response = await fetch("/api/admins");
//         if (!response.ok) throw new Error("Не удалось загрузить администраторов");
//
//         const admins = await response.json();
//         return admins.map((admin) => ({
//             id: admin.администратор_id,
//             name: admin.имя,
//             login: admin.логин,
//             role: "Admin",
//         }));
//     } catch (error) {
//         console.error(error.message);
//         return [];
//     }
// }
//
// /**
//  * Функция для загрузки суперадминистраторов
//  */
// async function loadSuperAdmins() {
//     try {
//         const response = await fetch("/api/superadmins");
//         if (!response.ok) throw new Error("Не удалось загрузить суперадминистраторов");
//
//         const superAdmins = await response.json();
//         return superAdmins.map((superAdmin) => ({
//             id: superAdmin.id,
//             name: superAdmin.имя,
//             login: superAdmin.логин,
//             role: "Superadmin",
//         }));
//     } catch (error) {
//         console.error(error.message);
//         return [];
//     }
// }
