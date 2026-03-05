import {
  MapPin,
  ExternalLink,
  Linkedin,
  Twitter,
  Instagram,
  Github,
  Globe,
  Facebook,
} from "lucide-react";

interface SocialLinks {
  linkedin?: string;
  twitter?: string;
  instagram?: string;
  github?: string;
  website?: string;
  facebook?: string;
}

interface ProfileCardProps {
  name: string;
  type?: string;
  role: string;
  location: string;
  description: string;
  profileImage?: string;
  socialLinks?: SocialLinks;
  href?: string;
}

export function ProfileCard({
  name,
  type,
  role,
  location,
  description,
  profileImage,
  socialLinks,
  href = "#",
}: ProfileCardProps) {
  return (
    <article className="bg-card border border-border rounded-lg p-6 hover:border-primary transition-colors focus-within:ring-2 focus-within:ring-primary h-full flex flex-col">
      {/* Header with badge and image */}
      <div className="flex items-start gap-4 mb-4">
        {/* Profile Image */}
        {profileImage ? (
          <img
            src={profileImage}
            alt={`Foto de ${name}`}
            className="w-20 h-20 rounded-2xl object-cover flex-shrink-0"
          />
        ) : (
          <div className="w-20 h-20 rounded-2xl bg-primary text-primary-foreground flex items-center justify-center flex-shrink-0 font-semibold text-2xl">
            {name
              .split(" ")
              .map((n) => n[0])
              .join("")
              .substring(0, 2)
              .toUpperCase()}
          </div>
        )}

        {/* Badge and basic info */}
        <div className="flex-1 min-w-0">
          {type && (
            <span className="inline-block bg-accent text-accent-foreground px-3 py-1 rounded text-sm mb-2">
              {type}
            </span>
          )}
          <h3 className="text-xl font-semibold mb-1">
            <a
              href={href}
              className="hover:text-primary focus:outline-none focus:underline inline-flex items-center gap-2"
              target="_blank"
              rel="noopener noreferrer"
            >
              {name}
              <ExternalLink className="w-4 h-4 flex-shrink-0" aria-hidden="true" />
            </a>
          </h3>
        </div>
      </div>

      {/* Role */}
      <p className="text-base mb-2">{role}</p>

      {/* Location */}
      <p className="text-sm text-muted-foreground flex items-center gap-1 mb-4">
        <MapPin className="w-4 h-4 flex-shrink-0" aria-hidden="true" />
        {location}
      </p>

      {/* Description */}
      <p className="text-muted-foreground mb-4 flex-1">{description}</p>

      {/* Social Links */}
      {socialLinks && Object.keys(socialLinks).length > 0 && (
        <div className="flex items-center gap-3 pt-4 border-t border-border">
          {socialLinks.linkedin && (
            <a
              href={socialLinks.linkedin}
              target="_blank"
              rel="noopener noreferrer"
              className="text-muted-foreground hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary rounded transition-colors"
              aria-label={`LinkedIn de ${name}`}
            >
              <Linkedin className="w-5 h-5" aria-hidden="true" />
            </a>
          )}
          {socialLinks.twitter && (
            <a
              href={socialLinks.twitter}
              target="_blank"
              rel="noopener noreferrer"
              className="text-muted-foreground hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary rounded transition-colors"
              aria-label={`Twitter/X de ${name}`}
            >
              <Twitter className="w-5 h-5" aria-hidden="true" />
            </a>
          )}
          {socialLinks.instagram && (
            <a
              href={socialLinks.instagram}
              target="_blank"
              rel="noopener noreferrer"
              className="text-muted-foreground hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary rounded transition-colors"
              aria-label={`Instagram de ${name}`}
            >
              <Instagram className="w-5 h-5" aria-hidden="true" />
            </a>
          )}
          {socialLinks.github && (
            <a
              href={socialLinks.github}
              target="_blank"
              rel="noopener noreferrer"
              className="text-muted-foreground hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary rounded transition-colors"
              aria-label={`GitHub de ${name}`}
            >
              <Github className="w-5 h-5" aria-hidden="true" />
            </a>
          )}
          {socialLinks.facebook && (
            <a
              href={socialLinks.facebook}
              target="_blank"
              rel="noopener noreferrer"
              className="text-muted-foreground hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary rounded transition-colors"
              aria-label={`Facebook de ${name}`}
            >
              <Facebook className="w-5 h-5" aria-hidden="true" />
            </a>
          )}
          {socialLinks.website && (
            <a
              href={socialLinks.website}
              target="_blank"
              rel="noopener noreferrer"
              className="text-muted-foreground hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary rounded transition-colors"
              aria-label={`Website de ${name}`}
            >
              <Globe className="w-5 h-5" aria-hidden="true" />
            </a>
          )}
        </div>
      )}
    </article>
  );
}
