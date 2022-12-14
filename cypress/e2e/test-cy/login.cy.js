describe("Login", () => {
    before(() => {
        // runs once before all tests in the block
    });

    beforeEach(() => {
        cy.visit("/home");
        // runs before each test in the block
    });

    afterEach(() => {
        // runs after each test in the block
        // cy.visit("/_testing/delete-booking");
    });

    after(() => {
        // runs once after all tests in the block
    });

    it("akan membuka halaman register dan mencoba register", () => {
        cy.visit("/register");

        cy.get('select[name="province"]').type("35", { force: true });
        cy.get('select[name="regency"]').type("3577", { force: true });
        cy.get('button[type="submit"]').eq(0).click({ force: true });

        cy.contains("Fave Hotel Madiun");
        cy.get("#link-hotel").eq(0).click({ force: true });

        cy.contains("Detail Hotel");
        cy.contains("Hotel Fave Hotel Madiun");
        cy.contains("Booking Hotel Ini");

        cy.get('input[name="name"]').type("From Cypress", { force: true });
        cy.get('input[name="email"]').type("cypress@gmail.com", {
            force: true,
        });
        cy.get('input[name="phone"]').type("081234567890", { force: true });
        cy.get('input[name="date_end"]').type("2022-11-24", { force: true });
        cy.get('input[type="submit"]').contains("Pesan");
        cy.get('input[type="submit"]').eq(0).click({ force: true });

        cy.contains("Informasi Booking");
        cy.contains("Fave Hotel Madiun");
        cy.contains("From Cypress");
        cy.contains("cypress@gmail.com");
        cy.contains("081234567890");
        cy.contains("Rp. 1.100.000");
    });

    it("will cancel a booking with no login", () => {
        cy.visit("/home");

        cy.get('select[name="province"]').type("35", { force: true });
        cy.get('select[name="regency"]').type("3577", { force: true });
        cy.get('button[type="submit"]').eq(0).click({ force: true });

        cy.contains("Fave Hotel Madiun");
        cy.get("#link-hotel").eq(0).click({ force: true });

        cy.contains("Detail Hotel");
        cy.contains("Hotel Fave Hotel Madiun");
        cy.contains("Booking Hotel Ini");

        cy.get('input[name="name"]').type("From Cypress", { force: true });
        cy.get('input[name="email"]').type("cypress@gmail.com", {
            force: true,
        });
        cy.get('input[name="phone"]').type("081234567890", { force: true });
        cy.get('input[name="date_end"]').type("2022-11-24", { force: true });
        cy.get('input[type="submit"]').contains("Pesan");
        cy.get('input[type="submit"]').eq(0).click({ force: true });

        cy.contains("Informasi Booking");
        cy.contains("Fave Hotel Madiun");
        cy.contains("From Cypress");
        cy.contains("cypress@gmail.com");
        cy.contains("081234567890");
        cy.contains("Rp. 1.100.000");

        cy.get(".btn-payment-cancel").eq(0).click({ force: true });
        cy.contains("Booking berhasil dibatalkan");
        cy.contains("Dibatalkan");
    });

    // it("will register a user", () => {
    //     cy.visit("/register");

    //     cy.get("input[name=name]").type("admin");
    //     cy.get("input[name=email]").type(
    //         "user" + new Date().valueOf() + "@mail.com"
    //     );
    //     cy.get("input[name=password]").type("password");
    //     cy.get("input[name=password_confirmation]").type("password");

    //     cy.get("button").contains("Register").click();

    //     cy.url().should("contain", "/home");
    // });

    // it("will log in a user", () => {
    //     cy.create("User").then((user) => {
    //         cy.visit("/login");

    //         cy.get('input[name="email"]').type(user.email);
    //         cy.get('input[name="password"]').type("password");
    //         cy.get('button[type="submit"]').click();

    //         cy.contains(user.name);
    //         cy.contains("You are logged in!");
    //     });
    // });

    // it("maintains user session", () => {
    //     cy.login();

    //     cy.visit("/home");

    //     cy.contains("You are logged in!");
    // });
});
