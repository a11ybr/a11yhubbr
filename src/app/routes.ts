import { createBrowserRouter } from "react-router";
import { Layout } from "./components/Layout";
import { Home } from "./pages/Home";
import { Content } from "./pages/Content";
import { Community } from "./pages/Community";
import { Events } from "./pages/Events";
import { Submit } from "./pages/Submit";
import { SubmitContent } from "./pages/SubmitContent";
import { SubmitEvent } from "./pages/SubmitEvent";
import { SubmitProfile } from "./pages/SubmitProfile";
import { About } from "./pages/About";
import { Newsletter } from "./pages/Newsletter";
import { NotFound } from "./pages/NotFound";

export const router = createBrowserRouter([
  {
    path: "/",
    Component: Layout,
    children: [
      {
        index: true,
        Component: Home,
      },
      {
        path: "conteudos",
        Component: Content,
      },
      {
        path: "comunidade",
        Component: Community,
      },
      {
        path: "eventos",
        Component: Events,
      },
      {
        path: "submeter",
        Component: Submit,
      },
      {
        path: "submeter/conteudo",
        Component: SubmitContent,
      },
      {
        path: "submeter/eventos",
        Component: SubmitEvent,
      },
      {
        path: "submeter/perfil",
        Component: SubmitProfile,
      },
      {
        path: "sobre",
        Component: About,
      },
      {
        path: "newsletter",
        Component: Newsletter,
      },
      {
        path: "*",
        Component: NotFound,
      },
    ],
  },
]);