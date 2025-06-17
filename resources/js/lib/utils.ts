import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export const mask_text = (text:string,visibleChars:number= 4,mask = '*') => {
  const masked = mask.repeat(text.length - visibleChars)
  return masked + text.slice(-visibleChars)
}
